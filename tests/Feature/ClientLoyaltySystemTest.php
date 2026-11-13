<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\LoyaltyCard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientLoyaltySystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Helper to authenticate as staff/admin
     */
    protected function authenticateAdmin(): User
    {
        $admin = User::first() ?? User::factory()->create();
        $this->actingAs($admin, 'web');

        return $admin;
    }

    /**
     * Helper to create and authenticate a client
     */
    protected function authenticateClient(array $attributes = []): Client
    {
        $client = Client::create(array_merge([
            'name' => 'Café de la Gare',
            'company_name' => 'Café de la Gare Casablanca',
            'contact_name' => 'Hassan Benjelloun',
            'email' => 'hassan@cafegare.ma',
            'password' => Hash::make('password123'),
            'access_code' => 'CG-CASA-2026',
            'status' => 'active',
            'loyalty_card_limit' => 5,
            'loyalty_enabled' => true,
        ], $attributes));

        $this->actingAs($client, 'client');

        return $client;
    }

    /**
     * Test admin can configure loyalty card quota when creating a client
     */
    public function test_admin_can_set_loyalty_card_limit_when_creating_client(): void
    {
        $this->authenticateAdmin();

        $response = $this->post(route('admin.clients.store'), [
            'name' => 'Boutique Mirage',
            'company_name' => 'Mirage Mode SARL',
            'contact_name' => 'Leila Chraibi',
            'email' => 'leila@mirage.ma',
            'status' => 'active',
            'access_code' => 'MIRAGE-2026-VIP',
            'loyalty_card_limit' => 50,
            'loyalty_enabled' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'email' => 'leila@mirage.ma',
            'loyalty_card_limit' => 50,
            'loyalty_enabled' => 1,
        ]);
    }

    /**
     * Test admin can update loyalty quota for an existing client
     */
    public function test_admin_can_update_loyalty_card_limit(): void
    {
        $this->authenticateAdmin();

        $client = Client::create([
            'name' => 'Boulangerie Parisienne',
            'company_name' => 'Boulangerie Parisienne',
            'contact_name' => 'Marc Dupont',
            'email' => 'marc@parisienne.ma',
            'status' => 'active',
            'loyalty_card_limit' => 10,
            'loyalty_enabled' => true,
        ]);

        $response = $this->put(route('admin.clients.update', $client), [
            'name' => 'Boulangerie Parisienne Deluxe',
            'company_name' => 'Boulangerie Parisienne Deluxe',
            'contact_name' => 'Marc Dupont',
            'email' => 'marc@parisienne.ma',
            'status' => 'active',
            'loyalty_card_limit' => 75,
            'loyalty_enabled' => 1,
        ]);

        $response->assertRedirect();
        $client->refresh();
        $this->assertEquals(75, $client->loyalty_card_limit);
    }

    /**
     * Test client can access loyalty cards dashboard and see quota metrics
     */
    public function test_client_can_view_loyalty_dashboard(): void
    {
        $client = $this->authenticateClient(['loyalty_card_limit' => 15]);

        $response = $this->get(route('client.loyalty.index'));

        $response->assertStatus(200);
        $response->assertSee('Programme Fidélité');
        $response->assertSee('15'); // card limit
        $response->assertSee('Générer une Carte');
    }

    /**
     * Test client can generate loyalty card when within admin quota
     */
    public function test_client_can_generate_loyalty_card_within_quota(): void
    {
        $client = $this->authenticateClient(['loyalty_card_limit' => 5]);

        $response = $this->post(route('client.loyalty.store'), [
            'customer_name' => 'Yasmine Bennani',
            'customer_phone' => '+212612345678',
            'customer_email' => 'yasmine@test.local',
            'card_type' => 'both',
            'nfc_uid' => '04:B2:1A:89:C4:5D:80',
            'max_stamps' => 8,
            'reward_text' => '1 Boisson Offerte',
            'initial_stamps' => 1,
        ]);

        $this->assertDatabaseHas('loyalty_cards', [
            'client_id' => $client->id,
            'customer_name' => 'Yasmine Bennani',
            'stamps_count' => 1,
            'max_stamps' => 8,
            'reward_text' => '1 Boisson Offerte',
            'card_type' => 'both',
            'nfc_uid' => '04:B2:1A:89:C4:5D:80',
        ]);

        $card = LoyaltyCard::where('customer_name', 'Yasmine Bennani')->first();
        $response->assertRedirect(route('client.loyalty.show', $card));
    }

    /**
     * Test client CANNOT generate cards when quota set by admin is exhausted
     */
    public function test_client_cannot_generate_cards_exceeding_admin_quota(): void
    {
        // Limit set strictly to 1 card
        $client = $this->authenticateClient(['loyalty_card_limit' => 1]);

        // Generate 1st card (successful)
        $client->loyaltyCards()->create([
            'card_code' => 'LC-TEST-0001',
            'customer_name' => 'First Customer',
            'max_stamps' => 10,
            'stamps_count' => 0,
            'reward_text' => 'Bonus',
            'card_type' => 'both',
        ]);

        $this->assertFalse($client->canGenerateLoyaltyCard());
        $this->assertEquals(0, $client->remainingLoyaltyCardsCount());

        // Attempt to create 2nd card via create form
        $createResponse = $this->get(route('client.loyalty.create'));
        $createResponse->assertRedirect(route('client.loyalty.index'));
        $createResponse->assertSessionHas('error');

        // Attempt to create 2nd card via POST
        $postResponse = $this->post(route('client.loyalty.store'), [
            'customer_name' => 'Second Customer (Should Fail)',
            'card_type' => 'both',
            'max_stamps' => 10,
            'reward_text' => 'Bonus',
        ]);

        $postResponse->assertRedirect(route('client.loyalty.index'));
        $postResponse->assertSessionHas('error');

        // Ensure 2nd card was NOT created in DB
        $this->assertDatabaseMissing('loyalty_cards', [
            'customer_name' => 'Second Customer (Should Fail)',
        ]);
    }

    /**
     * Test client cannot generate card when loyalty is disabled by admin
     */
    public function test_client_cannot_generate_card_when_loyalty_is_disabled(): void
    {
        $client = $this->authenticateClient([
            'loyalty_card_limit' => 20,
            'loyalty_enabled' => false,
        ]);

        $this->assertFalse($client->canGenerateLoyaltyCard());

        $response = $this->post(route('client.loyalty.store'), [
            'customer_name' => 'Disabled Member',
            'card_type' => 'both',
            'max_stamps' => 10,
            'reward_text' => 'Bonus',
        ]);

        $response->assertRedirect(route('client.loyalty.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test adding stamps to a loyalty card
     */
    public function test_client_can_punch_stamps_on_card(): void
    {
        $client = $this->authenticateClient();

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-STAMP-TEST',
            'customer_name' => 'Omar Kadiri',
            'stamps_count' => 2,
            'total_stamps_earned' => 2,
            'max_stamps' => 10,
            'reward_text' => '1 Déjeuner Offert',
            'card_type' => 'both',
        ]);

        $response = $this->post(route('client.loyalty.stamp', $card), [
            'count' => 3,
            'notes' => 'Achat menu VIP',
        ]);

        $response->assertRedirect();
        $card->refresh();

        $this->assertEquals(5, $card->stamps_count);
        $this->assertEquals(5, $card->total_stamps_earned);

        $this->assertDatabaseHas('loyalty_transactions', [
            'loyalty_card_id' => $card->id,
            'action' => 'stamp_added',
            'stamps_delta' => 3,
            'notes' => 'Achat menu VIP',
        ]);
    }

    /**
     * Test redeeming reward when stamps reach threshold
     */
    public function test_client_can_redeem_reward_when_stamps_threshold_reached(): void
    {
        $client = $this->authenticateClient();

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-REDEEM-TEST',
            'customer_name' => 'Nadia Chraibi',
            'stamps_count' => 8,
            'max_stamps' => 8,
            'reward_text' => '1 Menu Offert',
            'card_type' => 'both',
        ]);

        $this->assertTrue($card->isRewardAvailable());

        $response = $this->post(route('client.loyalty.redeem', $card), [
            'notes' => 'Menu débloqué en caisse',
        ]);

        $response->assertRedirect();
        $card->refresh();

        $this->assertEquals(0, $card->stamps_count);
        $this->assertEquals(1, $card->rewards_redeemed_count);

        $this->assertDatabaseHas('loyalty_transactions', [
            'loyalty_card_id' => $card->id,
            'action' => 'reward_redeemed',
            'stamps_delta' => -8,
        ]);
    }

    /**
     * Test redeeming reward is rejected when stamps are insufficient
     */
    public function test_client_cannot_redeem_reward_if_insufficient_stamps(): void
    {
        $client = $this->authenticateClient();

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-INSUFFICIENT',
            'customer_name' => 'Mehdi Alami',
            'stamps_count' => 4,
            'max_stamps' => 10,
            'reward_text' => '1 Café Offert',
            'card_type' => 'both',
        ]);

        $this->assertFalse($card->isRewardAvailable());

        $response = $this->post(route('client.loyalty.redeem', $card));
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $card->refresh();
        $this->assertEquals(4, $card->stamps_count);
        $this->assertEquals(0, $card->rewards_redeemed_count);
    }

    /**
     * Test strict tenant isolation: Client A cannot view, stamp or delete Client B's card
     */
    public function test_tenant_isolation_prevents_unauthorized_cross_client_access(): void
    {
        // Client A
        $clientA = $this->authenticateClient(['email' => 'clientA@test.ma', 'access_code' => 'CODE-A-1234']);

        // Client B
        $clientB = Client::create([
            'name' => 'Client B Shop',
            'company_name' => 'Client B Shop',
            'contact_name' => 'Owner B',
            'email' => 'clientB@test.ma',
            'access_code' => 'CODE-B-5678',
            'status' => 'active',
            'loyalty_card_limit' => 10,
        ]);

        $cardB = $clientB->loyaltyCards()->create([
            'card_code' => 'LC-CLIENT-B',
            'customer_name' => 'Client B VIP Customer',
            'stamps_count' => 5,
            'max_stamps' => 10,
            'reward_text' => 'Secret Reward',
            'card_type' => 'both',
        ]);

        // Client A attempts to view Client B's card
        $responseView = $this->get(route('client.loyalty.show', $cardB));
        $responseView->assertStatus(403);

        // Client A attempts to stamp Client B's card
        $responseStamp = $this->post(route('client.loyalty.stamp', $cardB), ['count' => 1]);
        $responseStamp->assertStatus(403);

        // Client A attempts to delete Client B's card
        $responseDelete = $this->delete(route('client.loyalty.destroy', $cardB));
        $responseDelete->assertStatus(403);
    }

    /**
     * Test public digital pass renders on smartphone with dynamic QR code and stamps
     */
    public function test_public_digital_pass_renders_successfully(): void
    {
        $client = Client::create([
            'name' => 'Palais des Saveurs',
            'company_name' => 'Palais des Saveurs Marrakech',
            'contact_name' => 'Chef Amine',
            'email' => 'amine@palais.ma',
            'status' => 'active',
            'access_code' => 'PALAIS-2026',
        ]);

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-PUBLIC-PASS',
            'customer_name' => 'Zineb Mansouri',
            'stamps_count' => 6,
            'max_stamps' => 10,
            'reward_text' => '1 Pâtisserie Maison Offerte',
            'card_type' => 'both',
        ]);

        $response = $this->get(route('loyalty.public.show', ['card_code' => 'LC-PUBLIC-PASS']));

        $response->assertStatus(200);
        $response->assertSee('Zineb Mansouri');
        $response->assertSee('LC-PUBLIC-PASS');
        $response->assertSee('1 Pâtisserie Maison Offerte');
        $response->assertSee('Palais des Saveurs Marrakech');
    }

    /**
     * Test deleting a loyalty card frees up quota for the client
     */
    public function test_deleting_loyalty_card_frees_up_quota(): void
    {
        $client = $this->authenticateClient(['loyalty_card_limit' => 1]);

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-TO-DELETE',
            'customer_name' => 'Temporary Member',
            'stamps_count' => 1,
            'max_stamps' => 10,
            'reward_text' => 'Bonus',
            'card_type' => 'both',
        ]);

        $this->assertEquals(0, $client->remainingLoyaltyCardsCount());

        // Delete card
        $response = $this->delete(route('client.loyalty.destroy', $card));
        $response->assertRedirect(route('client.loyalty.index'));
        $response->assertSessionHas('success');

        // Check soft-delete and quota restored
        $this->assertSoftDeleted('loyalty_cards', ['id' => $card->id]);
        $this->assertTrue($client->canGenerateLoyaltyCard());
        $this->assertEquals(1, $client->remainingLoyaltyCardsCount());
    }

    /**
     * Test client can configure loyalty point calculation rules and services catalog
     */
    public function test_client_can_configure_points_rules(): void
    {
        $client = $this->authenticateClient();

        $response = $this->post(route('client.loyalty.settings.update'), [
            'points_enabled' => 1,
            'calculation_mode' => 'both',
            'currency' => 'MAD',
            'spend_amount_ratio' => 20.00,
            'points_per_ratio' => 2,
            'min_spend_amount' => 50.00,
            'redemption_points' => 50,
            'redemption_value' => 15.00,
            'services' => [
                ['name' => 'Vidange Moteur', 'price' => 300, 'points' => 30],
                ['name' => 'Lavage Intérieur', 'price' => 80, 'points' => 10],
            ],
        ]);

        $response->assertRedirect(route('client.loyalty.settings'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('loyalty_program_settings', [
            'client_id' => $client->id,
            'currency' => 'MAD',
            'spend_amount_ratio' => 20.00,
            'points_per_ratio' => 2,
            'min_spend_amount' => 50.00,
            'redemption_points' => 50,
            'redemption_value' => 15.00,
        ]);
    }

    /**
     * Test POS terminal card lookup returns member profile and points calculation rules
     */
    public function test_terminal_card_lookup_via_ajax(): void
    {
        $client = $this->authenticateClient();

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-POS-LOOKUP',
            'customer_name' => 'Samir Berrada',
            'customer_phone' => '+212661223344',
            'points_balance' => 120,
            'stamps_count' => 3,
            'max_stamps' => 10,
            'reward_text' => 'Cadeau VIP',
            'card_type' => 'both',
        ]);

        $response = $this->postJson(route('client.loyalty.terminal.lookup'), [
            'code' => 'LC-POS-LOOKUP',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'card' => [
                'card_code' => 'LC-POS-LOOKUP',
                'customer_name' => 'Samir Berrada',
                'points_balance' => 120,
            ],
        ]);
    }

    /**
     * Test adding points automatically calculated based on money spent
     */
    public function test_terminal_adds_points_based_on_money_spent(): void
    {
        $client = $this->authenticateClient();

        // 10 DH = 1 pt
        $client->getLoyaltyProgramSetting()->update([
            'spend_amount_ratio' => 10.00,
            'points_per_ratio' => 1,
            'min_spend_amount' => 0,
        ]);

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-SPEND-TEST',
            'customer_name' => 'Kenza Tazi',
            'points_balance' => 10,
            'stamps_count' => 0,
            'max_stamps' => 10,
            'reward_text' => 'Bonus',
            'card_type' => 'both',
        ]);

        // Customer spent 250 DH -> should earn 25 points
        $response = $this->postJson(route('client.loyalty.terminal.process'), [
            'card_id' => $card->id,
            'operation' => 'add_spend',
            'amount' => 250.00,
            'notes' => 'Facture #4102',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'card' => [
                'points_balance' => 35, // 10 + 25 = 35
            ],
        ]);

        $card->refresh();
        $this->assertEquals(35, $card->points_balance);

        $this->assertDatabaseHas('loyalty_transactions', [
            'loyalty_card_id' => $card->id,
            'action' => 'points_earned',
            'points_delta' => 25,
        ]);
    }

    /**
     * Test deducting points from customer card for discount/reward
     */
    public function test_terminal_deducts_points_for_discount(): void
    {
        $client = $this->authenticateClient();

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-DEDUCT-TEST',
            'customer_name' => 'Rachid Fassi',
            'points_balance' => 150,
            'stamps_count' => 0,
            'max_stamps' => 10,
            'reward_text' => 'Bonus',
            'card_type' => 'both',
        ]);

        // Deduct 50 points
        $response = $this->postJson(route('client.loyalty.terminal.process'), [
            'card_id' => $card->id,
            'operation' => 'deduct_points',
            'points' => 50,
            'notes' => 'Remise appliquée sur ticket',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'card' => [
                'points_balance' => 100, // 150 - 50 = 100
            ],
        ]);

        $card->refresh();
        $this->assertEquals(100, $card->points_balance);

        $this->assertDatabaseHas('loyalty_transactions', [
            'loyalty_card_id' => $card->id,
            'action' => 'points_redeemed',
            'points_delta' => -50,
        ]);
    }

    /**
     * Test terminal prevents deducting more points than current balance
     */
    public function test_terminal_cannot_deduct_points_exceeding_balance(): void
    {
        $client = $this->authenticateClient();

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-OVER-DEDUCT',
            'customer_name' => 'Amina Slaoui',
            'points_balance' => 20,
            'stamps_count' => 0,
            'max_stamps' => 10,
            'reward_text' => 'Bonus',
            'card_type' => 'both',
        ]);

        // Attempt to deduct 50 points while balance is 20
        $response = $this->postJson(route('client.loyalty.terminal.process'), [
            'card_id' => $card->id,
            'operation' => 'deduct_points',
            'points' => 50,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);

        $card->refresh();
        $this->assertEquals(20, $card->points_balance);
    }

    /**
     * Test binding physical NFC UID to loyalty card after encoding
     */
    public function test_client_can_bind_nfc_uid_to_loyalty_card(): void
    {
        $client = $this->authenticateClient();

        $card = $client->loyaltyCards()->create([
            'card_code' => 'LC-NFC-BIND',
            'customer_name' => 'Tariq Benzakour',
            'points_balance' => 0,
            'stamps_count' => 0,
            'max_stamps' => 10,
            'reward_text' => 'Bonus',
            'card_type' => 'qr_code',
        ]);

        $response = $this->postJson(route('client.loyalty.bind-nfc', $card), [
            'nfc_uid' => '04:A2:3B:5C:8D:6E:90',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'nfc_uid' => '04:A2:3B:5C:8D:6E:90',
        ]);

        $card->refresh();
        $this->assertEquals('04:A2:3B:5C:8D:6E:90', $card->nfc_uid);
        $this->assertEquals('both', $card->card_type);
    }

    /**
     * Test binding physical NFC UID to NFC tag
     */
    public function test_client_can_bind_nfc_uid_to_nfc_tag(): void
    {
        $client = $this->authenticateClient();

        $tag = $client->nfcTags()->create([
            'tag_uid' => 'INITIAL-UID',
            'name' => 'Badge Porte Clé',
            'label' => 'Badge Porte Clé',
            'target_type' => 'url',
            'target_url' => 'https://example.com',
            'status' => 'active',
        ]);

        $response = $this->postJson(route('client.nfc-tags.bind', $tag), [
            'tag_uid' => '04:88:99:AA:BB:CC:DD',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'tag_uid' => '04:88:99:AA:BB:CC:DD',
        ]);

        $tag->refresh();
        $this->assertEquals('04:88:99:AA:BB:CC:DD', $tag->tag_uid);
    }

    /**
     * Test PWA manifest and service worker exist and are valid
     */
    public function test_pwa_manifest_and_service_worker_are_served(): void
    {
        $manifestResponse = $this->get('/manifest.json');
        $manifestResponse->assertStatus(200);
        $manifestResponse->assertJsonFragment([
            'short_name' => 'POS Terminal',
            'display' => 'standalone',
        ]);

        $swResponse = $this->get('/sw.js');
        $swResponse->assertStatus(200);
        $swResponse->assertSee('qrstudio-pos');
    }
}
