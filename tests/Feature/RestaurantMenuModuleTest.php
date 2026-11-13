<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\RestaurantMenu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RestaurantMenuModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'is_system' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin.menu@example.com',
            'username' => 'admin_menu',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->admin->roles()->sync([$role->id]);

        $this->client = Client::create([
            'company_name' => 'Burger Shack Lounge',
            'contact_name' => 'Karim Burger',
            'email' => 'karim@burgershack.test',
            'access_code' => 'BURGER123',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);
    }

    public function test_admin_can_view_menus_index_and_clients_with_menus(): void
    {
        $menu = RestaurantMenu::create([
            'client_id' => $this->client->id,
            'title' => 'Carte Gourmet',
            'slug' => 'burger-shack-carte-gourmet',
            'brand_name' => 'Burger Shack',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'status' => 'active',
            'categories' => [
                [
                    'name' => 'Burgers',
                    'items' => [
                        ['name' => 'Smash Truffe', 'price' => 75],
                    ],
                ],
            ],
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.menus.index'));

        $response->assertStatus(200);
        $response->assertSee('Menus Digitaux');
        $response->assertSee('Burger Shack');
        $response->assertSee('Carte Gourmet');
        $response->assertSee('Burger Shack Lounge');
    }

    public function test_admin_can_create_menu_with_categories_and_items(): void
    {
        $categoriesData = [
            [
                'id' => 'cat_1',
                'name' => 'Plats Signatures',
                'description' => 'Recettes du chef',
                'items' => [
                    [
                        'id' => 'it_1',
                        'name' => 'Entrecôte Grillée 300g',
                        'price' => 140,
                        'description' => 'Sauce poivre vert et frites maison',
                        'badge' => 'Chef',
                        'is_available' => true,
                    ],
                ],
            ],
        ];

        $postData = [
            'client_id' => $this->client->id,
            'brand_name' => 'Le Bistro Royal',
            'title' => 'Menu Dîner',
            'brand_type' => 'restaurant',
            'currency' => 'MAD',
            'description' => 'Cuisine de saison et grillades au feu de bois',
            'theme_color' => '#5736e9',
            'phone' => '+212522112233',
            'whatsapp' => '+212600112233',
            'status' => 'active',
            'categories_json' => json_encode($categoriesData),
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.menus.store'), $postData);

        $response->assertRedirect();
        $this->assertDatabaseHas('restaurant_menus', [
            'brand_name' => 'Le Bistro Royal',
            'title' => 'Menu Dîner',
            'client_id' => $this->client->id,
            'currency' => 'MAD',
        ]);

        $created = RestaurantMenu::where('brand_name', 'Le Bistro Royal')->first();
        $this->assertNotNull($created);
        $this->assertEquals(1, $created->total_categories_count);
        $this->assertEquals(1, $created->total_items_count);
    }

    public function test_admin_can_view_menu_details_and_download_qr(): void
    {
        $menu = RestaurantMenu::create([
            'client_id' => $this->client->id,
            'title' => 'Carte Boissons',
            'slug' => 'burger-shack-boissons',
            'brand_name' => 'Burger Shack',
            'brand_type' => 'cafe',
            'currency' => 'MAD',
            'status' => 'active',
            'categories' => [],
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.menus.show', $menu));
        $response->assertStatus(200);
        $response->assertSee('Carte Boissons');
        $response->assertSee('QR Code Table');

        // Test QR SVG download
        $qrResponse = $this->actingAs($this->admin, 'web')->get(route('admin.menus.download-qr', ['menu' => $menu, 'format' => 'svg']));
        $qrResponse->assertStatus(200);
        $this->assertEquals('image/svg+xml', $qrResponse->headers->get('Content-Type'));
    }

    public function test_admin_can_update_and_delete_menu(): void
    {
        $menu = RestaurantMenu::create([
            'client_id' => $this->client->id,
            'title' => 'Ancien Titre',
            'slug' => 'ancien-titre-menu',
            'brand_name' => 'Test Restaurant',
            'brand_type' => 'restaurant',
            'currency' => 'MAD',
            'status' => 'draft',
            'categories' => [],
        ]);

        // Update
        $updateResponse = $this->actingAs($this->admin, 'web')->put(route('admin.menus.update', $menu), [
            'client_id' => $this->client->id,
            'title' => 'Nouveau Titre Carte',
            'brand_name' => 'Test Restaurant Mis à Jour',
            'brand_type' => 'restaurant',
            'currency' => 'EUR',
            'status' => 'active',
            'categories_json' => json_encode([['name' => 'Desserts', 'items' => []]]),
        ]);

        $updateResponse->assertRedirect();
        $this->assertEquals('Nouveau Titre Carte', $menu->fresh()->title);
        $this->assertEquals('EUR', $menu->fresh()->currency);

        // Delete
        $deleteResponse = $this->actingAs($this->admin, 'web')->delete(route('admin.menus.destroy', $menu));
        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('restaurant_menus', ['id' => $menu->id]);
    }

    public function test_client_can_manage_their_own_menus_but_cannot_create_new_ones(): void
    {
        $menu = RestaurantMenu::create([
            'client_id' => $this->client->id,
            'title' => 'Menu Midi Express',
            'slug' => 'menu-midi-express',
            'brand_name' => 'Burger Shack Lounge',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'status' => 'active',
            'categories' => [
                [
                    'name' => 'Formules Midi',
                    'items' => [
                        ['name' => 'Menu Burger + Boisson', 'price' => 50],
                    ],
                ],
            ],
        ]);

        // 1. Client views list (no create button)
        $response = $this->actingAs($this->client, 'client')->get(route('client.menus.index'));
        $response->assertStatus(200);
        $response->assertSee('Mes Menus &amp; Cartes', false);
        $response->assertDontSee('Créer un Nouveau Menu');

        // 2. Client can edit & update assigned menu
        $editResponse = $this->actingAs($this->client, 'client')->get(route('client.menus.edit', $menu));
        $editResponse->assertStatus(200);

        $updateResponse = $this->actingAs($this->client, 'client')->put(route('client.menus.update', $menu), [
            'title' => 'Menu Midi Updated',
            'brand_name' => 'Burger Shack Lounge',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'status' => 'active',
            'categories_json' => json_encode([
                [
                    'name' => 'Formules Midi',
                    'items' => [
                        ['name' => 'Menu Burger + Boisson', 'price' => 55],
                    ],
                ],
            ]),
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('restaurant_menus', [
            'id' => $menu->id,
            'title' => 'Menu Midi Updated',
        ]);

        // 3. Client cannot create or delete menus
        $this->actingAs($this->client, 'client')->get('/client/menus/create')->assertStatus(404);
        $this->actingAs($this->client, 'client')->post('/client/menus', ['title' => 'Blocked'])->assertStatus(405);
        $this->actingAs($this->client, 'client')->delete("/client/menus/{$menu->id}")->assertStatus(405);
    }

    public function test_public_can_view_digital_menu_and_increments_views(): void
    {
        $menu = RestaurantMenu::create([
            'client_id' => $this->client->id,
            'title' => 'Carte Publique',
            'slug' => 'burger-shack-public-taste',
            'brand_name' => 'Burger Shack Official',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'theme_color' => '#5736e9',
            'views_count' => 5,
            'status' => 'active',
            'categories' => [
                [
                    'name' => 'Tacos Gratinés',
                    'items' => [
                        ['name' => 'Tacos Maxi Fromage', 'price' => 45, 'badge' => 'Populaire', 'is_available' => true],
                    ],
                ],
            ],
        ]);

        $response = $this->get(route('menu.public.show', $menu->slug));

        $response->assertStatus(200);
        $response->assertSee('Burger Shack Official');
        $response->assertSee('Tacos Gratinés');
        $response->assertSee('Tacos Maxi Fromage');
        $response->assertSee('45.00');

        $this->assertEquals(6, $menu->fresh()->views_count);
    }

    public function test_admin_can_upload_item_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('burger.jpg', 400, 400);

        $response = $this->actingAs($this->admin, 'web')->postJson(route('admin.menus.upload-item-image'), [
            'image' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertNotNull($response->json('url'));
    }

    public function test_admin_can_upload_cover_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('cover.jpg', 1200, 600);

        $response = $this->actingAs($this->admin, 'web')->postJson(route('admin.menus.upload-cover-image'), [
            'image' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertNotNull($response->json('url'));
    }

    public function test_menu_navigation_styles_vertical_horizontal_and_pages(): void
    {
        // 1. Create with vertical_scroll
        $menuVertical = RestaurantMenu::create([
            'client_id' => $this->client->id,
            'title' => 'Menu Vertical',
            'slug' => 'menu-vertical-test',
            'brand_name' => 'Burger Shack',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'nav_style' => 'vertical_scroll',
            'status' => 'active',
            'categories' => [
                ['name' => 'Burgers', 'items' => []],
            ],
        ]);

        $responseVertical = $this->get(route('menu.public.show', $menuVertical->slug));
        $responseVertical->assertStatus(200);
        $responseVertical->assertSee('navStyleVerticalContainer');

        // 2. Create with horizontal_scroll
        $menuHorizontal = RestaurantMenu::create([
            'client_id' => $this->client->id,
            'title' => 'Menu Horizontal',
            'slug' => 'menu-horizontal-test',
            'brand_name' => 'Burger Shack',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'nav_style' => 'horizontal_scroll',
            'status' => 'active',
            'categories' => [
                ['name' => 'Burgers', 'items' => []],
            ],
        ]);

        $responseHorizontal = $this->get(route('menu.public.show', $menuHorizontal->slug));
        $responseHorizontal->assertStatus(200);
        $responseHorizontal->assertSee('navStyleHorizontalContainer');
        $responseHorizontal->assertSee('horizontalScrollTrack');

        // 3. Create with pages
        $menuPages = RestaurantMenu::create([
            'client_id' => $this->client->id,
            'title' => 'Menu Pages',
            'slug' => 'menu-pages-test',
            'brand_name' => 'Burger Shack',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'nav_style' => 'pages',
            'status' => 'active',
            'categories' => [
                ['name' => 'Burgers', 'items' => []],
            ],
        ]);

        $responsePages = $this->get(route('menu.public.show', $menuPages->slug));
        $responsePages->assertStatus(200);
        $responsePages->assertSee('navStylePagesContainer');
        $responsePages->assertSee('pageNavArrows');
    }

    public function test_menu_colors_and_whatsapp_safe_spacer_and_card_containment(): void
    {
        $menu = RestaurantMenu::create([
            'client_id' => $this->client->id,
            'title' => 'Menu Couleurs & Style',
            'slug' => 'menu-couleurs-style-test',
            'brand_name' => 'Burger Shack Lounge',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'theme_color' => '#dc2626',
            'bg_color' => '#0f172a',
            'item_bg_color' => '#1e293b',
            'item_text_color' => '#f8fafc',
            'whatsapp' => '+212600112233',
            'nav_style' => 'vertical_scroll',
            'status' => 'active',
            'categories' => [
                [
                    'name' => 'Signature Burgers',
                    'items' => [
                        [
                            'name' => 'Smash Burger Extra Long Name With Many Ingredients',
                            'price' => 89.0,
                            'description' => 'Un burger avec des ingrédients exceptionnels et une description très longue qui ne doit jamais déborder de la carte.',
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->get(route('menu.public.show', $menu->slug));
        $response->assertStatus(200);

        // Check CSS variables for custom colors
        $response->assertSee('--menu-bg: #0f172a;', false);
        $response->assertSee('--item-bg: #1e293b;', false);
        $response->assertSee('--item-text: #f8fafc;', false);
        $response->assertSee('--brand-primary: #dc2626;', false);

        // Check WhatsApp spacer ensures content is not hidden
        $response->assertSee('whatsapp-bottom-spacer');
        $response->assertSee('whatsapp-floating');

        // Check card structure prevents content overflow
        $response->assertSee('item-info-col');
        $response->assertSee('item-name-text');
        $response->assertSee('item-desc-text');
    }

    public function test_menu_with_table_services_and_custom_item_colors_except_whatsapp(): void
    {
        $postData = [
            'client_id' => $this->client->id,
            'title' => 'Carte Table Services Pro',
            'brand_name' => 'Bistrot du Parc',
            'brand_type' => 'restaurant',
            'currency' => 'EUR',
            'status' => 'active',
            'theme_color' => '#f59e0b',
            'bg_color' => '#111827',
            'item_bg_color' => '#1f2937',
            'item_text_color' => '#f9fafb',
            'service_bg_color' => '#374151',
            'service_text_color' => '#e5e7eb',
            'table_service_enabled' => 1,
            'whatsapp' => '+212611223344',
            'whatsapp_message' => 'Bonjour, commande pour la {table} :',
            'wifi_ssid' => 'Bistrot_VIP',
            'wifi_password' => 'secretwifi2026',
            'phone' => '+212522001122',
            'address' => '15 Avenue Hassan II, Casablanca',
            'nav_style' => 'vertical_scroll',
            'categories_json' => json_encode([
                [
                    'id' => 'cat_drinks',
                    'name' => 'Boissons Fraîches',
                    'items' => [
                        [
                            'id' => 'it_lemonade',
                            'name' => 'Limonade Maison Menthe',
                            'price' => 35,
                            'description' => 'Citrons bio pressés et menthe fraîche',
                        ],
                    ],
                ],
            ]),
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.menus.store'), $postData);
        $response->assertRedirect();

        $menu = RestaurantMenu::where('title', 'Carte Table Services Pro')->first();
        $this->assertNotNull($menu);
        $this->assertEquals('#374151', $menu->service_bg_color);
        $this->assertEquals('#e5e7eb', $menu->service_text_color);
        $this->assertTrue($menu->table_service_enabled);
        $this->assertEquals('Bonjour, commande pour la {table} :', $menu->whatsapp_message);

        // View public menu
        $publicRes = $this->get(route('menu.public.show', $menu->slug));
        $publicRes->assertStatus(200);

        // Assert CSS variables
        $publicRes->assertSee('--service-bg: #374151;', false);
        $publicRes->assertSee('--service-text: #e5e7eb;', false);
        $publicRes->assertSee('--item-bg: #1f2937;', false);
        $publicRes->assertSee('--item-text: #f9fafb;', false);

        // Assert info badges display text as before
        $publicRes->assertSee('info-badge');
        $publicRes->assertSee('Bistrot_VIP');
        $publicRes->assertSee('secretwifi2026');
        $publicRes->assertSee('+212522001122');
        $publicRes->assertSee('15 Avenue Hassan II, Casablanca');

        // Assert WhatsApp button uses official green branding and is not overridden by item colors
        $publicRes->assertSee('btn-whatsapp-official');
        $publicRes->assertSee('#25D366 !important', false);
    }
}
