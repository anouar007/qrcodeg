<?php

namespace Tests\Feature;

use App\Models\QrCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QrTemplateStudioTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'is_system' => true,
        ]);

        $this->adminUser = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'adminuser',
            'email' => 'adminuser@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);
        $this->adminUser->roles()->sync([$adminRole->id]);
    }

    public function test_create_page_displays_studio_template_builder_elements(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.qr-codes.create'));

        $response->assertStatus(200);
        $response->assertSee('Studio Gabarit', false);
        $response->assertSee('studioCanvas');
        $response->assertSee('draggableQr');
        $response->assertSee('draggableCode');
        $response->assertSee('VPM ');
    }

    public function test_can_generate_qr_with_template_preset_and_custom_coordinates(): void
    {
        $config = [
            'qr_x' => 63.5,
            'qr_y' => 65.5,
            'qr_size_percent' => 23.0,
            'show_brackets' => true,
            'bracket_color' => '#d9a74a',
            'show_top_label' => true,
            'top_label_text' => 'Qr Code',
            'code_x' => 75.0,
            'code_y' => 86.8,
            'code_size' => 14,
            'code_color' => '#1e293b',
            'code_prefix' => 'VPM ',
        ];

        $payload = [
            'quantity' => 1,
            'name_prefix' => 'Plaque Avis Google',
            'type' => 'website',
            'url' => 'https://g.page/r/sample',
            'template_mode' => '1',
            'template_preset' => 'google_review_plaque',
            'custom_template_config' => json_encode($config),
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.qr-codes.store'), $payload);

        $response->assertRedirect(route('admin.qr-codes.index'));

        $qrCode = QrCode::where('name', 'Plaque Avis Google')->with('design')->first();
        $this->assertNotNull($qrCode);
        $this->assertNotNull($qrCode->design);
        $this->assertEquals('images/templates/google_review_plaque.jpg', $qrCode->design->template_image_path);
        $this->assertIsArray($qrCode->design->custom_template_config);
        $this->assertEquals(63.5, $qrCode->design->custom_template_config['qr_x']);
        $this->assertEquals('VPM ', $qrCode->design->custom_template_config['code_prefix']);
    }

    public function test_can_generate_qr_with_uploaded_template_image(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('custom_plaque.jpg', 600, 600);

        $payload = [
            'quantity' => 1,
            'name_prefix' => 'Custom Plaque',
            'type' => 'website',
            'url' => 'https://example.com/custom',
            'template_mode' => '1',
            'template_image' => $image,
            'custom_template_config' => json_encode([
                'qr_x' => 50.0,
                'qr_y' => 50.0,
                'qr_size_percent' => 30.0,
            ]),
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.qr-codes.store'), $payload);

        $response->assertRedirect(route('admin.qr-codes.index'));

        $qrCode = QrCode::where('name', 'Custom Plaque')->with('design')->first();
        $this->assertNotNull($qrCode);
        $this->assertStringStartsWith('qr_templates/', $qrCode->design->template_image_path);
        Storage::disk('public')->assertExists($qrCode->design->template_image_path);
    }

    public function test_composite_image_endpoint_and_download_render_successfully(): void
    {
        $qrCode = QrCode::create([
            'public_code' => 'VP-789123',
            'access_code' => 'SEC-998877',
            'name' => 'Test Composite Plaque',
            'type' => 'website',
            'status' => 'active',
            'dynamic_url' => url('/q/VP-789123'),
        ]);

        $qrCode->design()->create([
            'foreground_color' => '#000000',
            'background_color' => '#ffffff',
            'template_image_path' => 'images/templates/google_review_plaque.jpg',
            'custom_template_config' => [
                'qr_x' => 63.5,
                'qr_y' => 65.5,
                'qr_size_percent' => 23.0,
                'show_brackets' => true,
                'bracket_color' => '#d9a74a',
                'show_top_label' => true,
                'top_label_text' => 'Qr Code',
                'code_x' => 75.0,
                'code_y' => 86.8,
                'code_prefix' => 'VPM ',
                'code_size' => 14,
            ],
        ]);

        // 1. Direct composite image response
        $imgResponse = $this->actingAs($this->adminUser)
            ->get(route('admin.qr-codes.composite-image', $qrCode));

        $imgResponse->assertStatus(200);
        $imgResponse->assertHeader('Content-Type', 'image/png');
        $this->assertNotEmpty($imgResponse->getContent());

        // 2. Download composite format
        $dlResponse = $this->actingAs($this->adminUser)
            ->get(route('admin.qr-codes.download', [$qrCode, 'template']));

        $dlResponse->assertStatus(200);
        $dlResponse->assertHeader('Content-Type', 'image/png');
        $dlResponse->assertHeader('Content-Disposition');
    }
}
