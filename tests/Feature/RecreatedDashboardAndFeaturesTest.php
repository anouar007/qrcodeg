<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\DigitalCard;
use App\Models\NfcTag;
use App\Models\Permission;
use App\Models\QrCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecreatedDashboardAndFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrator']);
        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Test',
                'username' => 'admintest',
                'password' => bcrypt('password123'),
                'status' => 'active',
            ]
        );
        $this->admin->roles()->syncWithoutDetaching([$this->adminRole->id]);
    }

    /**
     * 1. Test Home Dashboard renders all 6 statistic sections
     */
    public function test_admin_dashboard_renders_all_6_statistic_sections(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Trafic Global');
        $response->assertSee('Section Clients');
        $response->assertSee('Section Cartes Visite Digitales');
        $response->assertSee('Section Tags & Cartes NFC', false);
        $response->assertSee('Section Dynamic QR Codes', false);
        $response->assertSee('Section Utilisateurs Internes', false);
        $response->assertSee('trafficTimelineChart');
    }

    /**
     * 2. Test Client Creation with explicit fields: name, city, phone, project name
     */
    public function test_client_creation_with_name_city_phone_and_project_name(): void
    {
        $postData = [
            'contact_name' => 'Youssef Alami',
            'project_name' => 'Campagne Printemps 2026',
            'city' => 'Casablanca',
            'phone' => '+212600112233',
            'email' => 'youssef@alami.ma',
            'company_name' => 'Alami Group',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.clients.store'), $postData);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'contact_name' => 'Youssef Alami',
            'project_name' => 'Campagne Printemps 2026',
            'city' => 'Casablanca',
            'phone' => '+212600112233',
            'email' => 'youssef@alami.ma',
        ]);
    }

    /**
     * 3. Test Client Show displays comprehensive historic timeline
     */
    public function test_client_show_displays_comprehensive_historic_timeline(): void
    {
        $client = Client::create([
            'contact_name' => 'Sara Mansouri',
            'project_name' => 'Projet Hôtellerie',
            'company_name' => 'Palais Mansour',
            'email' => 'sara@mansour.com',
            'city' => 'Marrakech',
            'phone' => '+212524000000',
            'status' => 'active',
            'access_code' => 'CL-MANSOUR01',
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.clients.show', $client));

        $response->assertStatus(200);
        $response->assertSee('Sara Mansouri');
        $response->assertSee('Projet Hôtellerie');
        $response->assertSee('Historique Complet');
        $response->assertSee('QR Codes');
        $response->assertSee('Cartes Visite');
        $response->assertSee('Tags NFC');
    }

    /**
     * 4. Test Digital Card creation, live preview, public view (/c/{slug}), and vCard download
     */
    public function test_digital_card_creation_and_public_view_and_vcard_download(): void
    {
        $cardData = [
            'name' => 'Carte Commerciale Youssef',
            'full_name' => 'Youssef Alami',
            'job_title' => 'Directeur des Ventes',
            'company' => 'Alami Group',
            'template' => 'executive',
            'bio' => 'Expert en transformation digitale et solutions sans-contact.',
            'badge_text' => '★ Membre Vérifié',
            'status' => 'active',
            'links' => [
                ['type' => 'whatsapp', 'title' => 'WhatsApp', 'value' => '+212600112233'],
                ['type' => 'website', 'title' => 'Site Web', 'value' => 'https://alami.ma'],
            ],
            'style_settings' => [
                'bg_color' => '#121212',
                'primary_color' => '#d4af37',
                'font_family' => 'Outfit',
                'border_radius' => '16px',
                'shadow_style' => 'soft',
            ],
            'layout_settings' => [
                'avatar_align' => 'center',
                'avatar_shape' => 'circle',
                'show_cover' => true,
                'show_badge' => true,
                'show_vcard_button' => true,
            ],
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.digital-cards.store'), $cardData);
        $response->assertRedirect(route('admin.digital-cards.index'));

        $card = DigitalCard::where('full_name', 'Youssef Alami')->firstOrFail();
        $this->assertStringStartsWith('CD-', $card->slug);

        // Public view test (/c/{slug})
        $publicResponse = $this->get(route('digital-cards.public.show', $card->slug));
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Youssef Alami');
        $publicResponse->assertSee('Directeur des Ventes');
        $publicResponse->assertSee('Enregistrer dans mes Contacts');

        // vCard download test
        $vcardResponse = $this->get(route('digital-cards.public.vcard', $card->slug));
        $vcardResponse->assertStatus(200);
        $vcardResponse->assertHeader('Content-Type', 'text/vcard; charset=utf-8');
        $this->assertStringContainsString('BEGIN:VCARD', $vcardResponse->getContent());
        $this->assertStringContainsString('FN;CHARSET=UTF-8:Youssef Alami', $vcardResponse->getContent());
    }

    /**
     * 5. Test NFC Tag creation, tap redirection (/n/{uid}) and analytics recording
     */
    public function test_nfc_tag_creation_and_tap_redirection_and_analytics(): void
    {
        $tagData = [
            'name' => 'Badge Porte-Clé Métal',
            'target_type' => 'url',
            'target_url' => 'https://instagram.com/mybrand',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.nfc-tags.store'), $tagData);
        $response->assertRedirect(route('admin.nfc-tags.index'));

        $tag = NfcTag::where('name', 'Badge Porte-Clé Métal')->firstOrFail();
        $this->assertStringStartsWith('NFC-', $tag->tag_uid);

        // Tap physical detection (/n/{uid})
        $tapResponse = $this->get(route('nfc.public.handle', $tag->tag_uid));
        $tapResponse->assertRedirect('https://instagram.com/mybrand');

        $tag->refresh();
        $this->assertEquals(1, $tag->tap_count);
        $this->assertDatabaseHas('nfc_taps', [
            'nfc_tag_id' => $tag->id,
        ]);
    }

    /**
     * 6. Test Dynamic QR VP prefix and in-dashboard destination modification without changing physical QR
     */
    public function test_dynamic_qr_vp_prefix_and_in_dashboard_destination_redirection(): void
    {
        // 1. Newly generated code must start with VP-
        $uniqueCode = QrCode::generateUniquePublicCode();
        $this->assertStringStartsWith('VP-', $uniqueCode);

        $qr = QrCode::create([
            'public_code' => $uniqueCode,
            'access_code' => 'ACC-VP001',
            'name' => 'Sticker Table Restaurant',
            'type' => 'website',
            'status' => 'active',
            'dynamic_url' => url('/q/'.$uniqueCode),
            'card_template' => 'menu',
            'target_redirect_url' => 'https://menu-initial.com/carte',
        ]);

        // Scanning QR routes to target_redirect_url
        $scanResponse = $this->get(url('/q/'.$qr->public_code));
        $scanResponse->assertRedirect('https://menu-initial.com/carte');

        // Admin updates destination without touching physical QR code
        $updateResponse = $this->actingAs($this->admin, 'web')->put(route('admin.qr-codes.destination', $qr), [
            'target_redirect_url' => 'https://menu-ete-2026.com/nouveau-menu',
            'card_template' => 'menu',
            'status' => 'active',
        ]);
        $updateResponse->assertRedirect();

        // Scanning the SAME printed QR code now routes to the new destination
        $scanResponse2 = $this->get(url('/q/'.$qr->public_code));
        $scanResponse2->assertRedirect('https://menu-ete-2026.com/nouveau-menu');
    }

    /**
     * 7. Test User creation with direct permissions checklist (not client)
     */
    public function test_user_creation_with_direct_permissions_checklist(): void
    {
        $perm1 = Permission::firstOrCreate(['slug' => 'digital_cards.create'], ['name' => 'Create Digital Cards', 'module' => 'digital_cards']);
        $perm2 = Permission::firstOrCreate(['slug' => 'nfc_tags.create'], ['name' => 'Create NFC Tags', 'module' => 'nfc_tags']);

        $userData = [
            'first_name' => 'Kamal',
            'last_name' => 'Berrada',
            'username' => 'kberrada',
            'email' => 'kamal@staff.com',
            'phone' => '+212611223344',
            'password' => 'secretStaff123',
            'password_confirmation' => 'secretStaff123',
            'status' => 'active',
            'permissions' => [$perm1->id, $perm2->id],
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.users.store'), $userData);
        $response->assertRedirect(route('admin.users.index'));

        $user = User::where('username', 'kberrada')->firstOrFail();
        $this->assertTrue($user->hasPermission('digital_cards.create'));
        $this->assertTrue($user->hasPermission('nfc_tags.create'));
        $this->assertFalse($user->hasPermission('settings.view'));
    }

    /**
     * 8. Test Printable PDF Card Generation for all 3 templates (google_review, social_media, menu)
     */
    public function test_printable_pdf_card_generation_with_3_templates(): void
    {
        $qr = QrCode::create([
            'public_code' => QrCode::generateUniquePublicCode(),
            'access_code' => 'ACC-PDFTEST',
            'name' => 'PDF Template Test QR',
            'type' => 'website',
            'status' => 'active',
            'dynamic_url' => url('/q/VP-PDFTEST01'),
        ]);

        foreach (['google_review', 'social_media', 'menu'] as $template) {
            $response = $this->actingAs($this->admin, 'web')->get(route('admin.qr-codes.card-pdf', [$qr, 'template' => $template]));
            $response->assertStatus(200);
            $response->assertHeader('Content-Type', 'application/pdf');
        }
    }
}
