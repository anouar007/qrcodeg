<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientHistory;
use App\Models\DigitalCard;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DigitalCardBuilderAndViewerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Client $client;

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

        $this->client = Client::create([
            'name' => 'Prestige Luxury Group',
            'project_name' => 'VIP Concierge 2026',
            'city' => 'Casablanca',
            'phone' => '+212600112233',
            'status' => 'active',
            'email' => 'concierge@prestige.ma',
        ]);
    }

    /**
     * 1. Test Digital Card Builder studio renders dual-pane and 3 presets
     */
    public function test_card_builder_studio_renders_dual_pane_and_presets(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.digital-cards.create'));
        $response->assertStatus(200);

        // 3 presets
        $response->assertSee('Modern Minimal');
        $response->assertSee('Corporate Sleek');
        $response->assertSee('Creative Bold');

        // Accordion sections
        $response->assertSee('Profile');
        $response->assertSee('Identity Details');
        $response->assertSee('Media Uploads');
        $response->assertSee('Styling Controls');
        $response->assertSee('Dynamic Link Repeater');

        // Mobile Device Simulator
        $response->assertSee('Live Device Simulator');
        $response->assertSee('phone-frame-mockup');

        // Dual creation modes: Option 1 (Classic) and Option 2 (Drag & Drop)
        $response->assertSee('Option 1 : Formulaire Standard');
        $response->assertSee('Option 2 : Glisser-Déposer', false);
        $response->assertSee('Bibliothèque d\'Éléments', false);
        $response->assertSee('Optimiser l\'Élément', false);
        $response->assertSee('dndDropZone');
    }

    /**
     * 2. Test Card creation with unique slug, theme_config, content_blocks, and media uploads
     */
    public function test_card_store_saves_theme_config_content_blocks_and_media(): void
    {
        Storage::fake('public');

        $profileFile = UploadedFile::fake()->image('avatar.jpg', 300, 300);
        $coverFile = UploadedFile::fake()->image('cover.jpg', 1200, 400);

        $themeConfig = [
            'primary_color' => '#4f46e5',
            'background_color' => '#0f172a',
            'font_family' => 'Outfit',
            'border_radius' => '18px',
            'shadow_style' => 'soft',
            'element_spacing' => '16px',
        ];

        $contentBlocks = [
            ['id' => 'blk_1', 'type' => 'contact', 'icon' => 'bi-telephone-fill', 'label' => 'Direct Mobile', 'url' => '+212600112233', 'order' => 1],
            ['id' => 'blk_2', 'type' => 'contact', 'icon' => 'bi-whatsapp', 'label' => 'WhatsApp VIP', 'url' => '+212600112233', 'order' => 2],
            ['id' => 'blk_3', 'type' => 'social', 'icon' => 'bi-linkedin', 'label' => 'LinkedIn Profile', 'url' => 'https://linkedin.com/in/marcus', 'order' => 3],
        ];

        $postData = [
            'title' => 'Directeur Général VIP Unique',
            'full_name' => 'Jean-Luc Picard',
            'job_title' => 'Managing Partner',
            'company' => 'Prestige Luxury Group',
            'bio' => 'Hospitality leader in bespoke digital touchpoints.',
            'badge_text' => '★ Managing Partner',
            'client_id' => $this->client->id,
            'template_id' => 'modern',
            'status' => 'active',
            'profile_photo' => $profileFile,
            'cover_photo' => $coverFile,
            'theme_config' => json_encode($themeConfig),
            'content_blocks' => json_encode($contentBlocks),
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.digital-cards.store'), $postData);
        $response->assertRedirect(route('admin.digital-cards.index'));

        $card = DigitalCard::where('full_name', 'Jean-Luc Picard')->first();
        $this->assertNotNull($card);
        $this->assertStringStartsWith('CD-', $card->slug);
        $this->assertEquals('Directeur Général VIP Unique', $card->title);
        $this->assertEquals('Managing Partner', $card->job_title);
        $this->assertEquals('#4f46e5', $card->theme_config['primary_color']);
        $this->assertEquals('Outfit', $card->theme_config['font_family']);
        $this->assertCount(3, $card->content_blocks);

        // Check storage
        Storage::disk('public')->assertExists($card->profile_photo_path);
        Storage::disk('public')->assertExists($card->cover_photo_path);

        // Check observer history
        $history = ClientHistory::where('client_id', $this->client->id)
            ->where('action_type', 'digital_card_created')
            ->first();
        $this->assertNotNull($history);
        $this->assertStringContainsString("Card 'Directeur Général VIP Unique' created", $history->description);
    }

    /**
     * 3. Test public standalone card viewer (/c/{slug}) and rate-limited views
     */
    public function test_public_card_viewer_and_rate_limiting(): void
    {
        Cache::flush();

        $card = DigitalCard::create([
            'client_id' => $this->client->id,
            'user_id' => $this->admin->id,
            'title' => 'Sophia Laurent VIP',
            'slug' => 'CD-SOPHIA99',
            'full_name' => 'Sophia Laurent',
            'job_title' => 'Chief Experience Officer',
            'company' => 'Prestige Luxury Group',
            'bio' => 'Curating elevated digital hospitality experiences.',
            'badge_text' => '★ Verified CXO',
            'template_id' => 'modern',
            'theme_config' => [
                'primary_color' => '#0ea5e9',
                'background_color' => '#0f172a',
                'font_family' => 'Outfit',
                'border_radius' => '16px',
                'shadow_style' => 'soft',
                'element_spacing' => '14px',
            ],
            'content_blocks' => [
                ['id' => 'b1', 'type' => 'contact', 'icon' => 'bi-telephone-fill', 'label' => 'Direct Phone', 'url' => '+212522001122', 'order' => 1],
                ['id' => 'b2', 'type' => 'custom_link', 'icon' => 'bi-globe', 'label' => 'VIP Portal', 'url' => 'https://prestige.ma', 'order' => 2],
            ],
            'views_count' => 10,
            'status' => 'active',
        ]);

        // 1st request -> should increment views_count to 11
        $response1 = $this->get('/c/'.$card->slug);
        $response1->assertStatus(200);
        $response1->assertSee('Sophia Laurent');
        $response1->assertSee('Chief Experience Officer');
        $response1->assertSee('Direct Phone');
        $response1->assertSee('Enregistrer dans mes Contacts');

        $card->refresh();
        $this->assertEquals(11, $card->views_count);

        // 2nd request immediately from same IP -> rate limited, should remain 11
        $response2 = $this->get('/c/'.$card->slug);
        $response2->assertStatus(200);

        $card->refresh();
        $this->assertEquals(11, $card->views_count);
    }

    /**
     * 4. Test vCard (.vcf) download endpoint (/c/{slug}/vcard)
     */
    public function test_vcard_download(): void
    {
        $card = DigitalCard::create([
            'client_id' => $this->client->id,
            'user_id' => $this->admin->id,
            'title' => 'Marcus Vance',
            'slug' => 'CD-MARCUS01',
            'full_name' => 'Marcus Vance',
            'job_title' => 'Managing Partner',
            'company' => 'Prestige Luxury Group',
            'bio' => 'Hospitality strategist.',
            'content_blocks' => [
                ['id' => 'b1', 'type' => 'phone', 'icon' => 'bi-telephone', 'label' => 'Phone', 'url' => '+212600112233', 'order' => 1],
                ['id' => 'b2', 'type' => 'email', 'icon' => 'bi-envelope', 'label' => 'Email', 'url' => 'marcus@prestige.ma', 'order' => 2],
            ],
            'status' => 'active',
        ]);

        $response = $this->get(route('digital-cards.public.vcard', $card->slug));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/vcard', $response->headers->get('content-type'));

        $content = $response->getContent();
        $this->assertStringContainsString('BEGIN:VCARD', $content);
        $this->assertStringContainsString('FN;CHARSET=UTF-8:Marcus Vance', $content);
        $this->assertStringContainsString('ORG;CHARSET=UTF-8:Prestige Luxury Group', $content);
        $this->assertStringContainsString('TITLE;CHARSET=UTF-8:Managing Partner', $content);
        $this->assertStringContainsString('TEL;TYPE=CELL:+212600112233', $content);
        $this->assertStringContainsString('EMAIL;TYPE=INTERNET:marcus@prestige.ma', $content);
        $this->assertStringContainsString('END:VCARD', $content);
    }
}
