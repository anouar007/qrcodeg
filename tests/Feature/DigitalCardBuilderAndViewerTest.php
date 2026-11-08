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

        // 3 presets & DOM radio inputs in Section 1
        $response->assertSee('id="sec-gabarits"', false);
        $response->assertSee('id="preset_modern"', false);
        $response->assertSee('id="preset_executive"', false);
        $response->assertSee('id="preset_creative"', false);
        $response->assertSee('Modern Minimal');
        $response->assertSee('Corporate Sleek');
        $response->assertSee('Creative Bold');

        // All 5 builder sections
        $response->assertSee('id="sec-identite"', false);
        $response->assertSee('id="sec-medias"', false);
        $response->assertSee('id="sec-style"', false);
        $response->assertSee('id="sec-canaux"', false);
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

        // Dynamic QR Engine CAD Design System parity
        $response->assertSee('studio-hero-card');
        $response->assertSee('status-dot-radar');
        $response->assertSee('studio-tabs-nav');
        $response->assertSee('studio-canvas-stage');
        $response->assertSee('studio-card');
        $response->assertSee('studio-step-badge');
        $response->assertSee('studio-input');
        $response->assertSee('studio-color-capsule');
        $response->assertSee('setSimulatorScale');

        // Menu category cover and item image upload capabilities
        $response->assertSee('uploadMenuImageAjax', false);
        $response->assertSee('menu-cat-cover-file', false);
        $response->assertSee('menu-item-file-upload', false);
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

    /**
     * 5. Test interactive Restaurant / Brand Menu configuration, link on public card, and menu navigation page (/c/{slug}/menu)
     */
    public function test_digital_card_interactive_menu_creation_and_public_navigation(): void
    {
        $menuConfig = [
            'enabled' => true,
            'title' => 'Carte Gastronomique & Signatures',
            'button_label' => 'Découvrir notre Menu Restaurant',
            'subtitle' => 'Plats faits maison & spécialités du chef',
            'currency' => 'MAD',
            'navigation_mode' => 'catalog',
            'item_layout' => 'grid',
            'design_preset' => 'luxury_gold',
            'show_search' => true,
            'show_images' => true,
            'categories' => [
                [
                    'id' => 'cat_starters',
                    'name' => 'Entrées & Tapas',
                    'icon' => 'fa-solid fa-bowl-food',
                    'items' => [
                        [
                            'id' => 'item_1',
                            'name' => 'Burrata Crémeuse aux Truffes',
                            'description' => 'Tomates anciennes, huile de truffe noire et basilic frais.',
                            'price' => '125',
                            'badge' => 'Signature',
                            'image_url' => 'https://example.com/burrata.jpg',
                            'available' => true,
                        ],
                    ],
                ],
                [
                    'id' => 'cat_mains',
                    'name' => 'Plats Principaux',
                    'icon' => 'fa-solid fa-utensils',
                    'items' => [
                        [
                            'id' => 'item_2',
                            'name' => 'Filet de Boeuf Rossini',
                            'description' => 'Foie gras poêlé, jus corsé et mousseline de pommes de terre.',
                            'price' => '290',
                            'badge' => 'Chef',
                            'available' => false,
                        ],
                    ],
                ],
            ],
        ];

        $postData = [
            'title' => 'Le Grand Café de la Marina',
            'full_name' => 'Chef Karim Bennani',
            'job_title' => 'Chef Exécutif',
            'company' => 'Marina Gastronomy',
            'bio' => 'Cuisine méditerranéenne de saison.',
            'client_id' => $this->client->id,
            'template_id' => 'modern',
            'status' => 'active',
            'theme_config' => json_encode([
                'primary_color' => '#ea580c',
                'background_color' => '#0f172a',
                'font_family' => 'Outfit',
                'border_radius' => '16px',
            ]),
            'content_blocks' => json_encode([
                ['id' => 'blk_menu', 'type' => 'menu', 'icon' => 'fa-solid fa-utensils', 'label' => 'Notre Carte Interactive', 'url' => '#menu', 'order' => 1],
            ]),
            'menu_config' => json_encode($menuConfig),
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.digital-cards.store'), $postData);
        $response->assertRedirect(route('admin.digital-cards.index'));

        $card = DigitalCard::where('full_name', 'Chef Karim Bennani')->first();
        $this->assertNotNull($card);
        $this->assertTrue($card->hasActiveMenu());
        $this->assertEquals('Carte Gastronomique & Signatures', $card->menu_config['title']);
        $this->assertCount(2, $card->menu_config['categories']);

        // Public digital card page (/c/{slug}) should show the Menu CTA button and link to /c/{slug}/menu
        $cardPage = $this->get(route('digital-cards.public.show', $card->slug));
        $cardPage->assertStatus(200);
        $cardPage->assertSee('Découvrir notre Menu Restaurant');
        $cardPage->assertSee(route('digital-cards.public.menu', $card->slug));

        // Public interactive menu page (/c/{slug}/menu) should render categories, items, prices, navigation switcher, and catalog pager
        $menuPage = $this->get(route('digital-cards.public.menu', $card->slug));
        $menuPage->assertStatus(200);
        $menuPage->assertSee('Carte Gastronomique & Signatures');
        $menuPage->assertSee('Entrées & Tapas');
        $menuPage->assertSee('Plats Principaux');
        $menuPage->assertSee('Burrata Crémeuse aux Truffes');
        $menuPage->assertSee('125');
        $menuPage->assertSee('Filet de Boeuf Rossini');
        $menuPage->assertSee('290');
        $menuPage->assertSee('Épuisé');
        $menuPage->assertSee('data-nav-mode="catalog"', false);
        $menuPage->assertSee('items-layout-grid', false);
        $menuPage->assertSee('catalogPagerBar', false);
        $menuPage->assertSee('Retour à la Carte');
    }

    /**
     * 6. Test dedicated Menu & Catalogue Studio page (/admin/digital-cards/{card}/menu) and update endpoint
     */
    public function test_dedicated_menu_studio_page_and_update(): void
    {
        $card = DigitalCard::create([
            'client_id' => $this->client->id,
            'user_id' => $this->admin->id,
            'title' => 'Maison Gourmet',
            'slug' => 'CD-GOURMET1',
            'full_name' => 'Maison Gourmet',
            'status' => 'active',
        ]);

        $studioGet = $this->actingAs($this->admin, 'web')->get(route('admin.digital-cards.menu.edit', $card));
        $studioGet->assertStatus(200);
        $studioGet->assertSee('Studio Menu & Catalogue Interactif', false);
        $studioGet->assertSee('1. Défilement Vertical');
        $studioGet->assertSee('2. Onglets Horizontaux');
        $studioGet->assertSee('3. Pages Type Catalogue');
        $studioGet->assertSee('applyMenuStarterTemplate');

        $newMenuConfig = [
            'enabled' => true,
            'title' => 'Catalogue Collection Hiver',
            'button_label' => 'Feuilleter le Catalogue',
            'subtitle' => 'Édition limitée 2026',
            'currency' => 'EUR',
            'navigation_mode' => 'catalog',
            'item_layout' => 'grid',
            'design_preset' => 'luxury_gold',
            'show_search' => true,
            'show_images' => true,
            'categories' => [
                [
                    'id' => 'cat_p1',
                    'name' => 'Montres & Horlogerie',
                    'icon' => 'fa-solid fa-clock',
                    'description' => 'Pièces suisses certifiées',
                    'items' => [
                        [
                            'id' => 'itm_w1',
                            'name' => 'Chronographe Royal Or',
                            'description' => 'Mouvement automatique calibre 41mm.',
                            'price' => '4500',
                            'badge' => 'Exclusif',
                            'available' => true,
                        ],
                    ],
                ],
            ],
        ];

        $studioPut = $this->actingAs($this->admin, 'web')->put(route('admin.digital-cards.menu.update', $card), [
            'menu_config' => json_encode($newMenuConfig),
        ]);
        $studioPut->assertRedirect(route('admin.digital-cards.menu.edit', $card));

        $card->refresh();
        $this->assertTrue($card->hasActiveMenu());
        $this->assertEquals('catalog', $card->menu_config['navigation_mode']);
        $this->assertEquals('grid', $card->menu_config['item_layout']);
        $this->assertEquals('luxury_gold', $card->menu_config['design_preset']);
        $this->assertEquals('Chronographe Royal Or', $card->menu_config['categories'][0]['items'][0]['name']);
    }

    /**
     * 7. Test image upload endpoint for menu covers and products
     */
    public function test_menu_image_upload_endpoint(): void
    {
        Storage::fake('public');

        $fakeImage = UploadedFile::fake()->image('dish-cover.jpg', 800, 600);

        $response = $this->actingAs($this->admin, 'web')->postJson(route('admin.digital-cards.upload-image'), [
            'image' => $fakeImage,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertNotEmpty($response->json('url'));
        $this->assertNotEmpty($response->json('path'));

        Storage::disk('public')->assertExists($response->json('path'));
    }

    /**
     * 8. Test public menu renders category page cover, product image, name, price, description, and badge
     */
    public function test_menu_renders_page_cover_and_product_image_name_price_description_badge(): void
    {
        $coverUrl = 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1000';
        $productImgUrl = 'https://images.unsplash.com/photo-1558030006-450675393462?w=600';

        $card = DigitalCard::create([
            'client_id' => $this->client->id,
            'user_id' => $this->admin->id,
            'title' => 'Bistro Royale VIP',
            'slug' => 'CD-ROYALE99',
            'full_name' => 'Bistro Royale VIP',
            'status' => 'active',
            'menu_config' => [
                'enabled' => true,
                'title' => 'Menu Signature & Vins',
                'button_label' => 'Découvrir la Carte Complète',
                'subtitle' => 'Gastronomie française raffinée',
                'currency' => 'EUR',
                'navigation_mode' => 'catalog',
                'item_layout' => 'grid',
                'design_preset' => 'bistro_dark',
                'accent_color' => '#d946ef',
                'show_search' => true,
                'show_images' => true,
                'categories' => [
                    [
                        'id' => 'cat_signatures',
                        'name' => 'Plats Signatures & Spécialités',
                        'icon' => 'fa-solid fa-crown',
                        'description' => 'Préparés par notre Chef étoilé',
                        'cover_image' => $coverUrl,
                        'items' => [
                            [
                                'id' => 'p_wagyu',
                                'name' => 'Côte de Wagyu A5 Flambée',
                                'description' => 'Mousseline de panais truffée et réduction au porto vintage.',
                                'price' => '180',
                                'badge' => 'Chef Étoilé',
                                'image_url' => $productImgUrl,
                                'available' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->get(route('digital-cards.public.menu', $card->slug));
        $response->assertStatus(200);

        // Assert Category Cover Banner as the Page Cover
        $response->assertSee('category-cover-banner', false);
        $response->assertSee('page-cover-banner', false);
        $response->assertSee('page-cover-title', false);
        $response->assertSee($coverUrl, false);
        $response->assertSee('Plats Signatures');
        $response->assertSee('Spécialités');
        $response->assertSee('Préparés par notre Chef étoilé');

        // Assert Product rendered as Card with Image Header, Badge, Name, Price, Description
        $response->assertSee('product-card-img-header', false);
        $response->assertSee('card-floating-badge', false);
        $response->assertSee('items-layout-grid', false);
        $response->assertSee('grid-template-columns: repeat(2, minmax(0, 1fr))', false);
        $response->assertSee($productImgUrl, false);
        $response->assertSee('Côte de Wagyu A5 Flambée');
        $response->assertSee('180');
        $response->assertSee('EUR');
        $response->assertSee('Mousseline de panais truffée et réduction au porto vintage.');
        $response->assertSee('Chef Étoilé');
        // Assert custom accent color is applied to buttons, prices and tabs
        $response->assertSee('#d946ef', false);

        // Assert Studio Menu Simulator shows Page Cover Image and 2 cards on each row
        $studioResponse = $this->actingAs($this->admin)->get(route('admin.digital-cards.menu.edit', $card->id));
        $studioResponse->assertStatus(200);
        $studioResponse->assertSee('Page Cover in Simulator', false);
        $studioResponse->assertSee('col-6', false);
        $studioResponse->assertSee('Grille de Cartes (2 cartes par ligne', false);
    }
}
