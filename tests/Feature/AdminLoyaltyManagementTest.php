<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\LoyaltyCard;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoyaltyManagementTest extends TestCase
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
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->admin->roles()->sync([$role->id]);

        $this->client = Client::create([
            'company_name' => 'Boutique Atlas',
            'contact_name' => 'Omar Atlas',
            'email' => 'omar@atlas.test',
            'access_code' => 'ATLAS777',
            'password' => bcrypt('secret123'),
            'status' => 'active',
            'loyalty_card_limit' => 30,
            'loyalty_enabled' => true,
        ]);
    }

    public function test_admin_can_view_loyalty_index(): void
    {
        $card = LoyaltyCard::create([
            'client_id' => $this->client->id,
            'card_code' => 'LC-ADMIN-TEST-1',
            'customer_name' => 'Fatima Zahra',
            'customer_email' => 'fatima@example.com',
            'points' => 250,
            'tier' => 'silver',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.loyalty.index'));

        $response->assertStatus(200);
        $response->assertSee('Cartes &amp; Programme Fidélité', false);
        $response->assertSee('LC-ADMIN-TEST-1');
        $response->assertSee('Fatima Zahra');
        $response->assertSee('Boutique Atlas');
        $response->assertSee('250 pts');
    }

    public function test_admin_can_filter_loyalty_cards(): void
    {
        LoyaltyCard::create([
            'client_id' => $this->client->id,
            'card_code' => 'LC-GOLD-99',
            'customer_name' => 'Youssef Gold',
            'points' => 600,
            'tier' => 'gold',
            'status' => 'active',
        ]);

        LoyaltyCard::create([
            'client_id' => $this->client->id,
            'card_code' => 'LC-BRONZE-11',
            'customer_name' => 'Karim Bronze',
            'points' => 50,
            'tier' => 'bronze',
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.loyalty.index', ['tier' => 'gold']));
        $response->assertStatus(200);
        $response->assertSee('LC-GOLD-99');
        $response->assertDontSee('LC-BRONZE-11');
    }

    public function test_admin_can_view_loyalty_card_details(): void
    {
        $card = LoyaltyCard::create([
            'client_id' => $this->client->id,
            'card_code' => 'LC-DETAIL-001',
            'customer_name' => 'Salma Detail',
            'customer_email' => 'salma@example.com',
            'customer_phone' => '+212600112233',
            'points' => 120,
            'tier' => 'bronze',
            'status' => 'active',
            'nfc_uid' => '04:A1:B2:C3:D4:E5:F6',
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.loyalty.show', $card));

        $response->assertStatus(200);
        $response->assertSee('LC-DETAIL-001');
        $response->assertSee('Salma Detail');
        $response->assertSee('04:A1:B2:C3:D4:E5:F6');
        $response->assertSee('120');
        $response->assertSee('Boutique Atlas');
    }

    public function test_admin_can_toggle_loyalty_card_status(): void
    {
        $card = LoyaltyCard::create([
            'client_id' => $this->client->id,
            'card_code' => 'LC-TOGGLE-01',
            'customer_name' => 'Nadia Status',
            'points' => 40,
            'tier' => 'bronze',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.loyalty.toggle-status', $card));

        $response->assertRedirect();
        $this->assertEquals('inactive', $card->fresh()->status);

        // Toggle back to active
        $response2 = $this->actingAs($this->admin, 'web')->post(route('admin.loyalty.toggle-status', $card));
        $response2->assertRedirect();
        $this->assertEquals('active', $card->fresh()->status);
    }

    public function test_admin_can_update_client_loyalty_limit_and_access(): void
    {
        $response = $this->actingAs($this->admin, 'web')->put(route('admin.loyalty.update-client-limit', $this->client), [
            'loyalty_card_limit' => 150,
            'loyalty_enabled' => 1,
        ]);

        $response->assertRedirect();
        $this->assertEquals(150, $this->client->fresh()->loyalty_card_limit);
        $this->assertTrue((bool) $this->client->fresh()->loyalty_enabled);
    }

    public function test_loyalty_index_only_shows_enrolled_clients_not_all(): void
    {
        // $this->client is enrolled ('loyalty_enabled' => true)
        // Create another client NOT enrolled in loyalty
        $otherClient = Client::create([
            'company_name' => 'General Client Without Loyalty',
            'contact_name' => 'John Doe',
            'email' => 'john.no.loyalty@example.test',
            'access_code' => 'NOLOYALTY1',
            'password' => bcrypt('secret123'),
            'status' => 'active',
            'loyalty_card_limit' => 0,
            'loyalty_enabled' => false,
        ]);

        $response = $this->actingAs($this->admin, 'web')->get(route('admin.loyalty.index'));
        $response->assertStatus(200);

        // Enrolled client IS in the loyalty clients list
        $response->assertSee('Boutique Atlas');

        // Verify view data: $clients ONLY contains enrolled clients, not all clients
        $enrolledClients = $response->viewData('clients');
        $this->assertTrue($enrolledClients->contains('id', $this->client->id));
        $this->assertFalse($enrolledClients->contains('id', $otherClient->id));

        // Available candidates modal contains the non-enrolled client
        $availableCandidates = $response->viewData('availableClients');
        $this->assertTrue($availableCandidates->contains('id', $otherClient->id));
        $this->assertFalse($availableCandidates->contains('id', $this->client->id));
    }

    public function test_admin_can_enroll_and_remove_client_from_loyalty(): void
    {
        $candidate = Client::create([
            'company_name' => 'Candidate Cafe',
            'contact_name' => 'Karim Cafe',
            'email' => 'karim@cafe.test',
            'access_code' => 'CAFE888',
            'password' => bcrypt('secret123'),
            'status' => 'active',
            'loyalty_card_limit' => 0,
            'loyalty_enabled' => false,
        ]);

        // Enroll candidate
        $enrollResponse = $this->actingAs($this->admin, 'web')->post(route('admin.loyalty.enroll-client'), [
            'client_id' => $candidate->id,
            'loyalty_card_limit' => 80,
        ]);

        $enrollResponse->assertRedirect();
        $this->assertTrue((bool) $candidate->fresh()->loyalty_enabled);
        $this->assertEquals(80, $candidate->fresh()->loyalty_card_limit);

        // Remove from loyalty
        $removeResponse = $this->actingAs($this->admin, 'web')->delete(route('admin.loyalty.remove-client', $candidate));
        $removeResponse->assertRedirect();
        $this->assertFalse((bool) $candidate->fresh()->loyalty_enabled);
    }
}
