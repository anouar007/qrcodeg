<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientHistory;
use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientManagementModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $adminRole = Role::firstOrCreate(['slug' => 'admin'], [
            'name' => 'Administrator',
            'is_system' => true,
        ]);

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@example.com'],
            [
                'name' => 'Admin Test',
                'first_name' => 'Admin',
                'last_name' => 'Test',
                'username' => 'admintest',
                'password' => bcrypt('password123'),
                'status' => 'active',
            ]
        );

        $this->admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }

    /**
     * Test client list view with search, filter, and pagination
     */
    public function test_client_index_search_and_filters(): void
    {
        $client1 = Client::create([
            'name' => 'Sara Mansouri',
            'project_name' => 'Palais Resort Marrakech',
            'city' => 'Marrakech',
            'phone' => '+212524000000',
            'status' => 'active',
            'email' => 'sara@palais.ma',
        ]);

        $client2 = Client::create([
            'name' => 'Karim Tazi',
            'project_name' => 'Casablanca Logistics',
            'city' => 'Casablanca',
            'phone' => '0661122334',
            'status' => 'inactive',
            'email' => 'karim@logistics.ma',
        ]);

        // 1. Unfiltered request
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.clients.index'));
        $response->assertStatus(200);
        $response->assertSee('Sara Mansouri');
        $response->assertSee('Karim Tazi');

        // 2. Search filter
        $searchResponse = $this->actingAs($this->admin, 'web')->get(route('admin.clients.index', ['search' => 'Mansouri']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Sara Mansouri');
        $searchResponse->assertDontSee('Karim Tazi');

        // 3. City filter
        $cityResponse = $this->actingAs($this->admin, 'web')->get(route('admin.clients.index', ['city' => 'Casablanca']));
        $cityResponse->assertStatus(200);
        $cityResponse->assertSee('Karim Tazi');
        $cityResponse->assertDontSee('Palais Resort Marrakech');

        // 4. Status filter
        $statusResponse = $this->actingAs($this->admin, 'web')->get(route('admin.clients.index', ['status' => 'active']));
        $statusResponse->assertStatus(200);
        $statusResponse->assertSee('Sara Mansouri');
        $statusResponse->assertDontSee('Karim Tazi');
    }

    /**
     * Test client CSV export
     */
    public function test_client_csv_export(): void
    {
        Client::create([
            'name' => 'Anas Chraibi',
            'project_name' => 'Digital Menu Project',
            'city' => 'Rabat',
            'phone' => '+212612345678',
            'status' => 'active',
            'email' => 'anas@menu.ma',
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.clients.export'));
        $response->assertStatus(200);
        $this->assertEquals('text/csv; charset=utf-8', $response->headers->get('content-type'));
    }

    /**
     * Test client creation with international and local phone format validation
     */
    public function test_client_store_validates_phone_and_creates_history(): void
    {
        // Invalid phone number format test
        $invalidResponse = $this->actingAs($this->admin, 'web')->post(route('admin.clients.store'), [
            'name' => 'Invalid Phone Client',
            'phone' => 'abc-not-a-number',
            'status' => 'active',
        ]);
        $invalidResponse->assertSessionHasErrors(['phone']);

        // Valid international phone number
        $validPost = [
            'name' => 'Yacine Belhadj',
            'project_name' => 'Hotel Touchless NFC',
            'city' => 'Tangier',
            'phone' => '+212661998877',
            'status' => 'active',
            'notes' => 'VIP luxury client with 10 physical NFC touch tags.',
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.clients.store'), $validPost);
        $response->assertRedirect();

        $client = Client::where('name', 'Yacine Belhadj')->first();
        $this->assertNotNull($client);
        $this->assertEquals('Tangier', $client->city);
        $this->assertEquals('+212661998877', $client->phone);

        // Observer must have automatically created a ClientHistory record
        $history = ClientHistory::where('client_id', $client->id)
            ->where('action_type', 'client_created')
            ->first();

        $this->assertNotNull($history);
        $this->assertStringContainsString('Yacine Belhadj', $history->description);
    }

    /**
     * Test client update triggers history with changed fields
     */
    public function test_client_update_records_audit_trail(): void
    {
        $client = Client::create([
            'name' => 'Leila Bennani',
            'project_name' => 'Initial Project',
            'city' => 'Fes',
            'phone' => '0535001122',
            'status' => 'active',
            'email' => 'leila@fes.ma',
        ]);

        $updateData = [
            'name' => 'Leila Bennani & Co',
            'project_name' => 'Updated Rebranding 2026',
            'city' => 'Meknes',
            'phone' => '+212535998877',
            'status' => 'active',
            'notes' => 'Updated headquarters city.',
        ];

        $response = $this->actingAs($this->admin, 'web')->put(route('admin.clients.update', $client), $updateData);
        $response->assertRedirect(route('admin.clients.show', $client));

        $client->refresh();
        $this->assertEquals('Meknes', $client->city);

        // Verify update timeline record exists
        $history = ClientHistory::where('client_id', $client->id)
            ->where('action_type', 'client_updated')
            ->latest('id')
            ->first();

        $this->assertNotNull($history);
        $this->assertStringContainsString('Profile updated', $history->description);
    }

    /**
     * Test client show view with assets and tabs
     */
    public function test_client_show_renders_key_stats_and_tabbed_interface(): void
    {
        $client = Client::create([
            'name' => 'Boutique Hotel Atlas',
            'project_name' => 'Atlas Smart Venue',
            'city' => 'Ifrane',
            'phone' => '+212535000000',
            'status' => 'active',
            'email' => 'concierge@atlas.ma',
        ]);

        // Create Digital Card linked to client
        $card = DigitalCard::create([
            'client_id' => $client->id,
            'user_id' => $this->admin->id,
            'title' => 'Concierge Digital Card',
            'slug' => 'cd-atlas-concierge',
            'template_id' => 'executive',
            'theme_config' => ['primary_color' => '#4f46e5'],
            'views_count' => 125,
            'status' => 'active',
        ]);

        // Create Dynamic QR linked to client
        $qr = DynamicQrCode::create([
            'client_id' => $client->id,
            'code' => 'VP-TEST99',
            'label' => 'Poolside Bar Menu',
            'target_url' => 'https://atlas.ma/pool-menu',
            'template_type' => 'menu',
            'scans_count' => 84,
            'status' => 'active',
        ]);

        // Create NFC Tag linked to client
        $nfc = NfcTag::create([
            'client_id' => $client->id,
            'label' => 'Room 101 Touch Key',
            'tag_uid' => 'NFC-ATL-101',
            'destination_url' => 'https://atlas.ma/room/101',
            'target_type' => 'url',
            'scans_count' => 45,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.clients.show', $client));
        $response->assertStatus(200);

        // Header key stats
        $response->assertSee('Boutique Hotel Atlas');
        $response->assertSee('Atlas Smart Venue');
        $response->assertSee('Concierge Digital Card');
        $response->assertSee('VP-TEST99');
        $response->assertSee('Room 101 Touch Key');
        $response->assertSee('cd-atlas-concierge');

        // Tab titles
        $response->assertSee('Cartes Visite');
        $response->assertSee('QR Codes');
        $response->assertSee('Tags NFC');
        $response->assertSee('Historique Complet');
    }

    /**
     * Test asset lifecycle creates timeline events: Card created, QR redirected
     */
    public function test_asset_observers_record_card_created_and_qr_redirected(): void
    {
        $client = Client::create([
            'name' => 'Grand Hotel Casablanca',
            'project_name' => 'Hotel QR Deployment',
            'city' => 'Casablanca',
            'phone' => '+212522112233',
            'status' => 'active',
            'email' => 'info@grandhotel.ma',
        ]);

        // 1. Digital card creation triggers history
        $card = DigitalCard::create([
            'client_id' => $client->id,
            'user_id' => $this->admin->id,
            'title' => 'VIP Card',
            'slug' => 'cd-grand-vip',
            'template_id' => 'modern',
            'status' => 'active',
        ]);

        $cardHistory = ClientHistory::where('client_id', $client->id)
            ->where('action_type', 'digital_card_created')
            ->first();

        $this->assertNotNull($cardHistory);
        $this->assertStringContainsString("Card 'VIP Card' created", $cardHistory->description);

        // 2. Dynamic QR creation triggers history
        $qr = DynamicQrCode::create([
            'client_id' => $client->id,
            'code' => 'VP-1029',
            'label' => 'Reception Feedback',
            'target_url' => 'https://grandhotel.ma/review',
            'template_type' => 'google_review',
            'status' => 'active',
        ]);

        // 3. Dynamic QR redirection triggers "QR VP-1029 redirected to URL Y"
        $qr->update([
            'target_url' => 'https://grandhotel.ma/new-promo-url',
        ]);

        $redirectHistory = ClientHistory::where('client_id', $client->id)
            ->where('description', 'like', '%redirected to URL%')
            ->first();

        $this->assertNotNull($redirectHistory);
        $this->assertEquals('QR VP-1029 redirected to URL https://grandhotel.ma/new-promo-url', $redirectHistory->description);
    }
}
