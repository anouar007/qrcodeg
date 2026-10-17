<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Permission;
use App\Models\QrCode;
use App\Models\QrContent;
use App\Models\QrDesign;
use App\Models\Role;
use App\Models\User;
use App\Services\QrCodeService;
use App\Services\QrDocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QrPlatformTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'is_system' => true,
        ]);

        $this->admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);
        $this->admin->roles()->sync([$this->adminRole->id]);
    }

    /**
     * Test Public Marketing Pages
     */
    public function test_public_pages_load_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Create, manage and customize your QR codes');

        $this->get('/features')->assertStatus(200);
        $this->get('/types')->assertStatus(200);
        $this->get('/pricing')->assertStatus(200);
        $this->get('/about')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
    }

    /**
     * Test Admin Authentication
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'login' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin, 'web');
    }

    public function test_inactive_user_cannot_login(): void
    {
        $this->admin->update(['status' => 'inactive']);

        $response = $this->post('/login', [
            'login' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest('web');
    }

    /**
     * Test Client Access & First-Time Password Setup
     */
    public function test_client_access_code_verification_and_first_time_password_setup(): void
    {
        $accessCode = Client::generateUniqueAccessCode();
        $client = Client::create([
            'company_name' => 'Seaside Resort',
            'contact_name' => 'Emily Green',
            'email' => 'emily@seaside.com',
            'access_code' => $accessCode,
            'status' => 'active',
            'is_password_set' => false,
        ]);

        // 1. Enter Access Code
        $verifyRes = $this->post('/client/access', [
            'access_code' => $accessCode,
        ]);
        $verifyRes->assertRedirect(route('client.password.setup'));
        $this->assertEquals($accessCode, session('pending_access_code'));

        // 2. Set Password on first login
        $pwdRes = $this->post('/client/password/setup', [
            'password' => 'secretPass123',
            'password_confirmation' => 'secretPass123',
        ]);

        $pwdRes->assertRedirect(route('client.dashboard'));
        $this->assertAuthenticatedAs($client->fresh(), 'client');
        $this->assertTrue($client->fresh()->is_password_set);
    }

    /**
     * Critical Mandatory Test:
     * Generate 1,000 QR codes and verify that all 1,000 public codes
     * and all 1,000 access codes are unique.
     */
    public function test_thousand_qr_codes_uniqueness(): void
    {
        $publicCodes = [];
        $accessCodes = [];
        $totalToGenerate = 1000;

        for ($i = 0; $i < $totalToGenerate; $i++) {
            $publicCode = QrCode::generateUniquePublicCode();
            $accessCode = QrCode::generateUniqueAccessCode();

            $publicCodes[$publicCode] = true;
            $accessCodes[$accessCode] = true;
        }

        $this->assertCount(
            $totalToGenerate,
            $publicCodes,
            'All 1,000 generated public codes must be strictly unique.'
        );
        $this->assertCount(
            $totalToGenerate,
            $accessCodes,
            'All 1,000 generated access codes must be strictly unique.'
        );
    }

    /**
     * Test Batch QR Code Generation in Service
     */
    public function test_qr_service_batch_generation(): void
    {
        $service = app(QrCodeService::class);
        $client = Client::create([
            'company_name' => 'Test Corp',
            'contact_name' => 'Test',
            'email' => 'corp@test.com',
            'status' => 'active',
        ]);

        $batch = $service->batchGenerate([
            'quantity' => 20,
            'name_prefix' => 'Badge',
            'client_id' => $client->id,
            'type' => 'website',
            'url' => 'https://example.com/badge',
        ], $this->admin);

        $this->assertCount(20, $batch);
        $this->assertDatabaseCount('qr_codes', 20);
        $this->assertDatabaseCount('qr_contents', 20);
        $this->assertDatabaseCount('qr_designs', 20);

        foreach ($batch as $qr) {
            $this->assertEquals($client->id, $qr->client_id);
            $this->assertStringStartsWith('QR-', $qr->public_code);
            $this->assertNotEmpty($qr->access_code);
        }
    }

    /**
     * Test Dynamic QR Scanning & Analytics Recording
     */
    public function test_dynamic_qr_scanning_records_scan_and_increments_count(): void
    {
        $qr = QrCode::create([
            'public_code' => 'QR-TEST001',
            'access_code' => 'TEST-0001-CODE',
            'name' => 'Test WiFi QR',
            'type' => 'wifi',
            'status' => 'active',
            'dynamic_url' => url('/q/QR-TEST001'),
        ]);

        QrContent::create([
            'qr_code_id' => $qr->id,
            'type' => 'wifi',
            'content_data' => ['ssid' => 'TestWiFi', 'password' => 'Pass123'],
        ]);

        QrDesign::create([
            'qr_code_id' => $qr->id,
            'foreground_color' => '#1e3a8a',
            'background_color' => '#ffffff',
        ]);

        $this->assertEquals(0, $qr->scan_count);

        // Perform scan request with mock user-agent
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
        ])->get('/q/QR-TEST001');

        $response->assertStatus(200);
        $response->assertSee('TestWiFi');

        $qr->refresh();
        $this->assertEquals(1, $qr->scan_count);
        $this->assertNotNull($qr->last_scanned_at);

        $this->assertDatabaseHas('qr_scans', [
            'qr_code_id' => $qr->id,
            'device_type' => 'mobile',
            'operating_system' => 'iOS',
            'browser' => 'Safari',
        ]);
    }

    /**
     * Test QR Suspension and Inactivity
     */
    public function test_suspended_qr_code_shows_unavailable_view(): void
    {
        $qr = QrCode::create([
            'public_code' => 'QR-SUSPENDED',
            'access_code' => 'SUSP-0000-0000',
            'name' => 'Paused QR',
            'type' => 'website',
            'status' => 'suspended',
            'dynamic_url' => url('/q/QR-SUSPENDED'),
        ]);

        $response = $this->get('/q/QR-SUSPENDED');
        $response->assertStatus(200);
        $response->assertSee('This QR code is currently unavailable');
    }

    /**
     * Test Granular Role and Permission Authorization
     */
    public function test_role_permissions_authorization(): void
    {
        $viewClientsPerm = Permission::create([
            'name' => 'View Clients',
            'slug' => 'view_clients',
            'module' => 'clients',
        ]);

        $staffRole = Role::create([
            'name' => 'Restricted Staff',
            'slug' => 'restricted_staff',
            'is_system' => false,
        ]);

        $staffUser = User::create([
            'first_name' => 'Staff',
            'last_name' => 'Person',
            'username' => 'staff1',
            'email' => 'staff1@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);
        $staffUser->roles()->sync([$staffRole->id]);

        $this->assertFalse($staffUser->hasPermission('view_clients'));

        // Assign permission to role
        $staffRole->permissions()->sync([$viewClientsPerm->id]);
        $staffUser->refresh();
        $this->assertTrue($staffUser->hasPermission('view_clients'));
    }

    /**
     * Test Printable QR Document Generation
     */
    public function test_printable_qr_document_generation(): void
    {
        $docService = app(QrDocumentService::class);

        $qr1 = QrCode::create([
            'public_code' => 'QR-DOC01',
            'access_code' => 'DOC1-KEY-0001',
            'name' => 'Room 101',
            'type' => 'website',
            'status' => 'active',
            'dynamic_url' => url('/q/QR-DOC01'),
        ]);
        QrDesign::create(['qr_code_id' => $qr1->id]);

        $qr2 = QrCode::create([
            'public_code' => 'QR-DOC02',
            'access_code' => 'DOC2-KEY-0002',
            'name' => 'Room 102',
            'type' => 'website',
            'status' => 'active',
            'dynamic_url' => url('/q/QR-DOC02'),
        ]);
        QrDesign::create(['qr_code_id' => $qr2->id]);

        $doc = $docService->createDocument([
            'title' => 'Resort Batch A4',
            'paper_size' => 'a4',
            'columns' => 2,
            'rows' => 4,
            'qr_code_ids' => [$qr1->id, $qr2->id],
        ], $this->admin);

        $this->assertEquals('Resort Batch A4', $doc->title);
        $this->assertCount(2, $doc->items);

        // Verify PDF can be compiled
        $pdf = $docService->generatePdf($doc);
        $output = $pdf->output();
        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF', $output);
    }
}
