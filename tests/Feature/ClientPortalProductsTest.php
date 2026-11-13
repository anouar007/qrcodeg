<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientPortalProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test client login page renders with email/password and access code options
     */
    public function test_client_login_page_renders_with_dual_tabs(): void
    {
        $response = $this->get(route('client.login'));

        $response->assertStatus(200);
        $response->assertSee('Espace Client');
        $response->assertSee('Email &amp; Mot de passe', false);
        $response->assertSee('Clé d\'Accès Produit', false);
        $response->assertSee(route('client.login.submit'));
        $response->assertSee(route('client.access.verify'));
    }

    /**
     * Test client can authenticate with email and password
     */
    public function test_client_can_login_with_email_and_password(): void
    {
        $client = Client::create([
            'name' => 'Royal Mansour Luxury',
            'company_name' => 'Royal Mansour Marrakech',
            'contact_name' => 'Karim Alami',
            'email' => 'karim@royalmansour.ma',
            'password' => Hash::make('password123'),
            'access_code' => 'RM-LUX-2026',
            'status' => 'active',
        ]);

        $response = $this->post(route('client.login.submit'), [
            'email' => 'karim@royalmansour.ma',
            'password' => 'password123',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('client.dashboard'));
        $this->assertAuthenticatedAs($client, 'client');
    }

    /**
     * Test client cannot login with wrong password
     */
    public function test_client_cannot_login_with_invalid_password(): void
    {
        Client::create([
            'name' => 'Atlas Resort',
            'email' => 'atlas@resort.ma',
            'password' => Hash::make('secret_pass'),
            'access_code' => 'ATLAS-01',
            'status' => 'active',
        ]);

        $response = $this->from(route('client.login'))->post(route('client.login.submit'), [
            'email' => 'atlas@resort.ma',
            'password' => 'wrong_password',
        ]);

        $response->assertRedirect(route('client.login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest('client');
    }

    /**
     * Test inactive client cannot login
     */
    public function test_inactive_client_cannot_login(): void
    {
        Client::create([
            'name' => 'Suspended Client',
            'email' => 'suspended@client.com',
            'password' => Hash::make('secret123'),
            'access_code' => 'SUSP-01',
            'status' => 'inactive',
        ]);

        $response = $this->post(route('client.login.submit'), [
            'email' => 'suspended@client.com',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest('client');
    }

    /**
     * Test client can view products hub and ONLY sees their own products (strict isolation)
     */
    public function test_client_can_view_products_hub_with_strict_ownership_scoping(): void
    {
        // Client A
        $clientA = Client::create([
            'name' => 'Hotel Client A',
            'email' => 'clientA@hotel.com',
            'password' => Hash::make('secretA'),
            'access_code' => 'CODE-AAA-01',
            'status' => 'active',
        ]);

        // Client A Products
        $cardA = DigitalCard::create([
            'client_id' => $clientA->id,
            'title' => 'Executive Card A',
            'slug' => 'exec-card-a',
            'status' => 'active',
            'views_count' => 50,
        ]);

        $qrA = DynamicQrCode::create([
            'client_id' => $clientA->id,
            'code' => 'VP-AAA111',
            'label' => 'Dynamic QR A',
            'target_url' => 'https://client-a.com/review',
            'status' => 'active',
            'scans_count' => 120,
        ]);

        $nfcA = NfcTag::create([
            'client_id' => $clientA->id,
            'label' => 'NFC Tag A',
            'tag_uid' => 'NFC-AAA-99',
            'destination_url' => 'https://client-a.com/welcome',
            'status' => 'active',
            'scans_count' => 40,
        ]);

        // Client B
        $clientB = Client::create([
            'name' => 'Hotel Client B',
            'email' => 'clientB@hotel.com',
            'password' => Hash::make('secretB'),
            'access_code' => 'CODE-BBB-02',
            'status' => 'active',
        ]);

        // Client B Products
        $cardB = DigitalCard::create([
            'client_id' => $clientB->id,
            'title' => 'Secret Card B',
            'slug' => 'secret-card-b',
            'status' => 'active',
            'views_count' => 999,
        ]);

        $qrB = DynamicQrCode::create([
            'client_id' => $clientB->id,
            'code' => 'VP-BBB222',
            'label' => 'Confidential QR B',
            'target_url' => 'https://client-b.com/secret',
            'status' => 'active',
            'scans_count' => 888,
        ]);

        $nfcB = NfcTag::create([
            'client_id' => $clientB->id,
            'label' => 'Secret NFC Tag B',
            'tag_uid' => 'NFC-BBB-88',
            'destination_url' => 'https://client-b.com/vip',
            'status' => 'active',
            'scans_count' => 777,
        ]);

        // Act as Client A
        $response = $this->actingAs($clientA, 'client')->get(route('client.products.index'));

        $response->assertStatus(200);

        // Client A MUST see their own products
        $response->assertSee('Executive Card A');
        $response->assertSee('VP-AAA111');
        $response->assertSee('Dynamic QR A');
        $response->assertSee('NFC Tag A');
        $response->assertSee('NFC-AAA-99');

        // Client A MUST NOT see Client B's products
        $response->assertDontSee('Secret Card B');
        $response->assertDontSee('VP-BBB222');
        $response->assertDontSee('Confidential QR B');
        $response->assertDontSee('Secret NFC Tag B');
        $response->assertDontSee('NFC-BBB-88');
    }

    /**
     * Test dynamic QR codes isolation and client update authorization
     */
    public function test_client_dynamic_qrs_isolation_and_target_updating(): void
    {
        $clientA = Client::create([
            'name' => 'Client Alpha',
            'email' => 'alpha@client.com',
            'password' => Hash::make('passAlpha'),
            'access_code' => 'ALPHA-01',
            'status' => 'active',
        ]);

        $qrA = DynamicQrCode::create([
            'client_id' => $clientA->id,
            'code' => 'VP-ALPHA1',
            'label' => 'Reception Desk QR',
            'target_url' => 'https://alpha.com/menu',
            'status' => 'active',
        ]);

        $clientB = Client::create([
            'name' => 'Client Beta',
            'email' => 'beta@client.com',
            'password' => Hash::make('passBeta'),
            'access_code' => 'BETA-02',
            'status' => 'active',
        ]);

        $qrB = DynamicQrCode::create([
            'client_id' => $clientB->id,
            'code' => 'VP-BETA99',
            'label' => 'Beta Secret QR',
            'target_url' => 'https://beta.com/private',
            'status' => 'active',
        ]);

        // 1. Client A visits list -> sees QR A, not QR B
        $indexResponse = $this->actingAs($clientA, 'client')->get(route('client.dynamic-qrs.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('VP-ALPHA1');
        $indexResponse->assertDontSee('VP-BETA99');

        // 2. Client A updates their own QR A
        $updateResponse = $this->actingAs($clientA, 'client')->put(route('client.dynamic-qrs.update', $qrA), [
            'label' => 'Updated Reception QR',
            'target_url' => 'https://alpha.com/new-menu-2026',
            'template_type' => 'menu',
        ]);
        $updateResponse->assertRedirect(route('client.dynamic-qrs.index'));
        $qrA->refresh();
        $this->assertEquals('https://alpha.com/new-menu-2026', $qrA->target_url);
        $this->assertEquals('Updated Reception QR', $qrA->label);

        // 3. Client A tries to edit Client B's QR -> 403 Forbidden
        $unauthorizedEdit = $this->actingAs($clientA, 'client')->get(route('client.dynamic-qrs.edit', $qrB));
        $unauthorizedEdit->assertStatus(403);

        // 4. Client A tries to update Client B's QR -> 403 Forbidden
        $unauthorizedUpdate = $this->actingAs($clientA, 'client')->put(route('client.dynamic-qrs.update', $qrB), [
            'label' => 'Hacked QR',
            'target_url' => 'https://hacked.com',
        ]);
        $unauthorizedUpdate->assertStatus(403);
        $qrB->refresh();
        $this->assertEquals('https://beta.com/private', $qrB->target_url);
    }

    /**
     * Test digital cards isolation and client update authorization
     */
    public function test_client_digital_cards_isolation_and_updating(): void
    {
        $clientA = Client::create([
            'name' => 'Studio Alpha',
            'email' => 'studio@alpha.com',
            'password' => Hash::make('pass123'),
            'access_code' => 'STUDIO-A',
            'status' => 'active',
        ]);

        $cardA = DigitalCard::create([
            'client_id' => $clientA->id,
            'title' => 'Directeur General Alpha',
            'slug' => 'alpha-ceo',
            'job_title' => 'CEO',
            'company' => 'Studio Alpha',
            'status' => 'active',
        ]);

        $clientB = Client::create([
            'name' => 'Studio Beta',
            'email' => 'studio@beta.com',
            'password' => Hash::make('pass456'),
            'access_code' => 'STUDIO-B',
            'status' => 'active',
        ]);

        $cardB = DigitalCard::create([
            'client_id' => $clientB->id,
            'title' => 'Directeur General Beta',
            'slug' => 'beta-ceo',
            'job_title' => 'Managing Director',
            'company' => 'Studio Beta',
            'status' => 'active',
        ]);

        // 1. Client A visits list -> sees Card A, not Card B
        $indexResponse = $this->actingAs($clientA, 'client')->get(route('client.digital-cards.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Directeur General Alpha');
        $indexResponse->assertDontSee('Directeur General Beta');

        // 2. Client A updates their own Card A
        $updateResponse = $this->actingAs($clientA, 'client')->put(route('client.digital-cards.update', $cardA), [
            'title' => 'President Directeur General Alpha',
            'full_name' => 'Amine Bennani',
            'job_title' => 'President',
            'company' => 'Studio Alpha Group',
            'phone' => '+212600112233',
            'email' => 'amine@alpha.com',
            'bio' => 'Dirigeant passionne par les solutions sans contact.',
            'status' => 'active',
        ]);
        $updateResponse->assertRedirect(route('client.digital-cards.index'));
        $cardA->refresh();
        $this->assertEquals('President Directeur General Alpha', $cardA->title);
        $this->assertEquals('Amine Bennani', $cardA->full_name);

        // 3. Client A tries to edit Client B's Card -> 403 Forbidden
        $unauthorizedEdit = $this->actingAs($clientA, 'client')->get(route('client.digital-cards.edit', $cardB));
        $unauthorizedEdit->assertStatus(403);

        // 4. Client A tries to update Client B's Card -> 403 Forbidden
        $unauthorizedUpdate = $this->actingAs($clientA, 'client')->put(route('client.digital-cards.update', $cardB), [
            'title' => 'Hacked Card',
            'status' => 'active',
        ]);
        $unauthorizedUpdate->assertStatus(403);
        $cardB->refresh();
        $this->assertEquals('Directeur General Beta', $cardB->title);
    }

    /**
     * Test NFC tags isolation and client destination swap authorization
     */
    public function test_client_nfc_tags_isolation_and_destination_updating(): void
    {
        $clientA = Client::create([
            'name' => 'Lounge Club A',
            'email' => 'club@a.com',
            'password' => Hash::make('loungeA'),
            'access_code' => 'LOUNGE-A',
            'status' => 'active',
        ]);

        $nfcA = NfcTag::create([
            'client_id' => $clientA->id,
            'label' => 'Table VIP 1 NFC',
            'tag_uid' => 'NFC-VIP-01',
            'destination_url' => 'https://lounge-a.com/vip1',
            'target_type' => 'url',
            'status' => 'active',
        ]);

        $clientB = Client::create([
            'name' => 'Lounge Club B',
            'email' => 'club@b.com',
            'password' => Hash::make('loungeB'),
            'access_code' => 'LOUNGE-B',
            'status' => 'active',
        ]);

        $nfcB = NfcTag::create([
            'client_id' => $clientB->id,
            'label' => 'Table VIP 2 NFC',
            'tag_uid' => 'NFC-VIP-02',
            'destination_url' => 'https://lounge-b.com/vip2',
            'target_type' => 'url',
            'status' => 'active',
        ]);

        // 1. Client A visits list -> sees Tag A, not Tag B
        $indexResponse = $this->actingAs($clientA, 'client')->get(route('client.nfc-tags.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Table VIP 1 NFC');
        $indexResponse->assertDontSee('Table VIP 2 NFC');

        // 2. Client A updates their own Tag A
        $updateResponse = $this->actingAs($clientA, 'client')->put(route('client.nfc-tags.update', $nfcA), [
            'label' => 'Table VIP 1 Gold Touch',
            'target_type' => 'url',
            'destination_url' => 'https://lounge-a.com/new-cocktail-menu',
        ]);
        $updateResponse->assertRedirect(route('client.nfc-tags.index'));
        $nfcA->refresh();
        $this->assertEquals('https://lounge-a.com/new-cocktail-menu', $nfcA->destination_url);
        $this->assertEquals('Table VIP 1 Gold Touch', $nfcA->label);

        // 3. Client A tries to edit Client B's Tag -> 403 Forbidden
        $unauthorizedEdit = $this->actingAs($clientA, 'client')->get(route('client.nfc-tags.edit', $nfcB));
        $unauthorizedEdit->assertStatus(403);

        // 4. Client A tries to update Client B's Tag -> 403 Forbidden
        $unauthorizedUpdate = $this->actingAs($clientA, 'client')->put(route('client.nfc-tags.update', $nfcB), [
            'label' => 'Hacked NFC',
            'target_type' => 'url',
            'destination_url' => 'https://hacked.com',
        ]);
        $unauthorizedUpdate->assertStatus(403);
        $nfcB->refresh();
        $this->assertEquals('https://lounge-b.com/vip2', $nfcB->destination_url);
    }

    /**
     * Test admin can set client login & password directly, and client can log in but cannot create products
     */
    public function test_admin_can_set_client_login_credentials_and_client_cannot_create_products(): void
    {
        $role = Role::where('slug', 'admin')->first() ?? Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'is_system' => true,
        ]);
        $admin = User::factory()->create();
        $admin->roles()->sync([$role->id]);

        $client = Client::create([
            'name' => 'Partner Cafe',
            'email' => 'old-cafe@example.com',
            'access_code' => 'CAFE-9999',
            'status' => 'active',
        ]);

        // Admin sets login email & password
        $setResponse = $this->actingAs($admin, 'web')->post(route('admin.clients.set-credentials', $client), [
            'email' => 'owner@partnercafe.com',
            'password' => 'CafePass2026!',
        ]);

        $setResponse->assertRedirect();
        $setResponse->assertSessionHas('generated_credentials');

        $client->refresh();
        $this->assertEquals('owner@partnercafe.com', $client->email);
        $this->assertTrue((bool) $client->is_password_set);
        $this->assertTrue(Hash::check('CafePass2026!', $client->password));

        // Client logs in with the new email & password
        $loginResponse = $this->post(route('client.login.submit'), [
            'email' => 'owner@partnercafe.com',
            'password' => 'CafePass2026!',
        ]);

        $loginResponse->assertRedirect(route('client.dashboard'));
        $this->assertAuthenticatedAs($client, 'client');

        // Client can also log in using their access_code as login + password
        $this->post(route('client.logout'));
        $codeLoginResponse = $this->post(route('client.login.submit'), [
            'email' => 'CAFE-9999',
            'password' => 'CafePass2026!',
        ]);
        $codeLoginResponse->assertRedirect(route('client.dashboard'));
        $this->assertAuthenticatedAs($client, 'client');

        // Client cannot create new products (menus, digital cards, dynamic QRs, NFC tags, standard QRs)
        $this->actingAs($client, 'client')->get('/client/menus/create')->assertStatus(404);
        $this->actingAs($client, 'client')->post('/client/menus', ['title' => 'New Menu'])->assertStatus(405);
        $this->assertContains($this->actingAs($client, 'client')->get('/client/digital-cards/create')->status(), [404, 405]);
        $this->assertContains($this->actingAs($client, 'client')->get('/client/dynamic-qrs/create')->status(), [404, 405]);
        $this->assertContains($this->actingAs($client, 'client')->get('/client/nfc-tags/create')->status(), [404, 405]);
        $this->assertContains($this->actingAs($client, 'client')->get('/client/qr-codes/create')->status(), [404, 405]);
    }
}
