<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientHistory;
use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use App\Models\ScanLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NfcTagManagementModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@nfcmodule.test',
            'status' => 'active',
        ]);

        $this->client = Client::create([
            'name' => 'Royal Mansour Resort',
            'email' => 'tech@royalmansour.test',
            'project_name' => 'Smart Hotel Tags',
            'city' => 'Marrakech',
            'phone' => '+212524388888',
            'status' => 'active',
        ]);
    }

    /**
     * 1. Test NFC tags index list view displays correctly with required columns
     */
    public function test_nfc_tags_index_view_renders_with_tags_and_metrics(): void
    {
        $tag = NfcTag::create([
            'client_id' => $this->client->id,
            'label' => 'Presidential Suite Keycard',
            'tag_uid' => 'NFC-ROYAL-001',
            'destination_url' => 'https://royalmansour.com/suite/1',
            'scans_count' => 12,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.nfc-tags.index'));

        $response->assertStatus(200);
        $response->assertSee('NFC Tag Management');
        $response->assertSee('NFC-ROYAL-001');
        $response->assertSee('Presidential Suite Keycard');
        $response->assertSee('Royal Mansour Resort');
        $response->assertSee('https://royalmansour.com/suite/1');
        $response->assertSee('12');
    }

    /**
     * 2. Test registering a new NFC tag with optional hardware UID
     */
    public function test_register_nfc_tag_with_custom_hardware_uid(): void
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.nfc-tags.store'), [
                'label' => 'Bar Counter Tap Stand',
                'tag_uid' => '04:A1:B2:C3:D4:E5:F6',
                'destination_url' => 'https://royalmansour.com/bar-menu',
                'client_id' => $this->client->id,
                'status' => 'active',
                'notes' => 'Embedded on mahogany bar counter',
            ]);

        $response->assertRedirect(route('admin.nfc-tags.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('nfc_tags', [
            'label' => 'Bar Counter Tap Stand',
            'tag_uid' => '04:A1:B2:C3:D4:E5:F6',
            'destination_url' => 'https://royalmansour.com/bar-menu',
            'client_id' => $this->client->id,
            'status' => 'active',
        ]);

        // Assert ClientHistory recorded
        $history = ClientHistory::where('client_id', $this->client->id)
            ->where('action_type', 'nfc_tag_created')
            ->first();

        $this->assertNotNull($history);
        $this->assertStringContainsString('04:A1:B2:C3:D4:E5:F6', $history->description);
    }

    /**
     * 3. Test registering a tag with auto-generated UID when left blank
     */
    public function test_register_nfc_tag_auto_generates_uid_when_blank(): void
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.nfc-tags.store'), [
                'label' => 'Spa Wellness Touchpoint',
                'tag_uid' => '', // Left empty
                'destination_url' => 'https://royalmansour.com/spa',
                'client_id' => $this->client->id,
                'status' => 'active',
            ]);

        $response->assertRedirect(route('admin.nfc-tags.index'));

        $tag = NfcTag::where('label', 'Spa Wellness Touchpoint')->first();
        $this->assertNotNull($tag);
        $this->assertNotNull($tag->tag_uid);
        $this->assertStringStartsWith('NFC-', $tag->tag_uid);
    }

    /**
     * 4. Test selecting an existing Digital Card preset automatically links target URL
     */
    public function test_register_nfc_tag_with_digital_card_preset(): void
    {
        $card = DigitalCard::create([
            'client_id' => $this->client->id,
            'title' => 'General Manager Card',
            'slug' => 'gm-mansour',
            'theme_config' => ['primary_color' => '#1e3a8a'],
            'content_blocks' => [],
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.nfc-tags.store'), [
                'label' => 'GM Executive Metal Card',
                'preset_target' => 'card:'.$card->id,
                'client_id' => $this->client->id,
                'status' => 'active',
            ]);

        $response->assertRedirect(route('admin.nfc-tags.index'));

        $tag = NfcTag::where('label', 'GM Executive Metal Card')->first();
        $this->assertNotNull($tag);
        $this->assertEquals(route('digital-cards.public.show', 'gm-mansour'), $tag->destination_url);
        $this->assertEquals('digital_card', $tag->target_type);
        $this->assertEquals($card->id, $tag->digital_card_id);
    }

    /**
     * 5. Test selecting an existing Dynamic QR (VP-) preset automatically links target URL
     */
    public function test_register_nfc_tag_with_dynamic_qr_preset(): void
    {
        $dqr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-556677',
            'label' => 'Restaurant Menu QR',
            'target_url' => 'https://royalmansour.com/dining',
            'template_type' => 'menu',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.nfc-tags.store'), [
                'label' => 'Table #1 NFC Tag',
                'preset_target' => 'qr:'.$dqr->id,
                'client_id' => $this->client->id,
                'status' => 'active',
            ]);

        $response->assertRedirect(route('admin.nfc-tags.index'));

        $tag = NfcTag::where('label', 'Table #1 NFC Tag')->first();
        $this->assertNotNull($tag);
        $this->assertEquals(route('qr.redirect', ['code' => 'VP-556677']), $tag->destination_url);
    }

    /**
     * 6. Test public redirect route /nfc/{id_or_uid} via UID
     */
    public function test_nfc_redirect_route_via_uid_logs_telemetry_and_redirects(): void
    {
        $tag = NfcTag::create([
            'client_id' => $this->client->id,
            'label' => 'Lobby Concierge Tag',
            'tag_uid' => 'NFC-LOBBY-01',
            'destination_url' => 'https://royalmansour.com/concierge',
            'scans_count' => 0,
            'status' => 'active',
        ]);

        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '105.158.42.10',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) Mobile/15E148',
        ])->get(route('nfc.redirect', ['id_or_uid' => 'NFC-LOBBY-01']));

        $response->assertStatus(302);
        $response->assertRedirect('https://royalmansour.com/concierge');

        $tag->refresh();
        $this->assertEquals(1, $tag->scans_count);

        // Verify ScanLog polymorphic telemetry recorded
        $scanLog = ScanLog::where('trackable_type', 'NfcTag')
            ->where('trackable_id', $tag->id)
            ->first();

        $this->assertNotNull($scanLog);
        $this->assertEquals('105.158.42.10', $scanLog->ip_address);
        $this->assertStringContainsString('iPhone', $scanLog->user_agent);
        $this->assertNotNull($scanLog->scanned_at);
        $this->assertEquals($tag->id, $scanLog->trackable->id);
    }

    /**
     * 7. Test public redirect route /nfc/{id_or_uid} via numeric ID
     */
    public function test_nfc_redirect_route_via_numeric_id_works_identically(): void
    {
        $tag = NfcTag::create([
            'client_id' => $this->client->id,
            'label' => 'Pool Cabana Tag',
            'tag_uid' => 'NFC-CABANA-09',
            'destination_url' => 'https://royalmansour.com/cabana',
            'scans_count' => 5,
            'status' => 'active',
        ]);

        $response = $this->get(route('nfc.redirect', ['id_or_uid' => $tag->id]));

        $response->assertStatus(302);
        $response->assertRedirect('https://royalmansour.com/cabana');

        $tag->refresh();
        $this->assertEquals(6, $tag->scans_count);
    }

    /**
     * 8. Test inactive / suspended NFC tag does not redirect
     */
    public function test_inactive_nfc_tag_displays_unavailable_message(): void
    {
        $tag = NfcTag::create([
            'client_id' => $this->client->id,
            'label' => 'Lost Card',
            'tag_uid' => 'NFC-LOST-999',
            'destination_url' => 'https://example.com/expired',
            'status' => 'inactive',
        ]);

        $response = $this->get(route('nfc.redirect', ['id_or_uid' => 'NFC-LOST-999']));

        $response->assertStatus(200);
        $response->assertSee('NFC Tag Unavailable');
    }

    /**
     * 9. Test quick destination swapping and Client History audit trail integration
     */
    public function test_quick_destination_swapping_updates_target_and_records_client_history(): void
    {
        $tag = NfcTag::create([
            'client_id' => $this->client->id,
            'label' => 'Summer Campaign Badge',
            'tag_uid' => 'NFC-SWAP-TEST',
            'destination_url' => 'https://royalmansour.com/summer-promo',
            'status' => 'active',
        ]);

        // Client changes campaign: swap to Autumn festival URL
        $response = $this->actingAs($this->admin, 'web')
            ->put(route('admin.nfc-tags.destination', $tag), [
                'destination_url' => 'https://royalmansour.com/autumn-festival',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $tag->refresh();
        $this->assertEquals('https://royalmansour.com/autumn-festival', $tag->destination_url);
        // UID remains completely unchanged
        $this->assertEquals('NFC-SWAP-TEST', $tag->tag_uid);

        // Verify Client History module integration recorded the URL modification
        $history = ClientHistory::where('client_id', $this->client->id)
            ->where('description', 'like', "%NFC Tag 'Summer Campaign Badge' (UID: NFC-SWAP-TEST) redirected to URL https://royalmansour.com/autumn-festival%")
            ->first();

        $this->assertNotNull($history);

        // Verify subsequent taps immediately redirect to the new URL
        $tap = $this->get(route('nfc.redirect', ['id_or_uid' => 'NFC-SWAP-TEST']));
        $tap->assertStatus(302);
        $tap->assertRedirect('https://royalmansour.com/autumn-festival');
    }

    /**
     * 10. Test quick destination swapping via AJAX JSON request
     */
    public function test_quick_destination_swapping_via_ajax_json(): void
    {
        $tag = NfcTag::create([
            'client_id' => $this->client->id,
            'label' => 'Conference Keynote Stand',
            'tag_uid' => 'NFC-CONF-2026',
            'destination_url' => 'https://old-keynote.com',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->putJson(route('admin.nfc-tags.destination', $tag), [
                'destination_url' => 'https://new-live-slides.com/presentation',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'destination_url' => 'https://new-live-slides.com/presentation',
            'old_destination_url' => 'https://old-keynote.com',
        ]);

        $tag->refresh();
        $this->assertEquals('https://new-live-slides.com/presentation', $tag->destination_url);
    }

    /**
     * 11. Test status toggle action (active <-> inactive)
     */
    public function test_status_toggle_action(): void
    {
        $tag = NfcTag::create([
            'client_id' => $this->client->id,
            'label' => 'Gate Tag',
            'tag_uid' => 'NFC-GATE-1',
            'destination_url' => 'https://royalmansour.com/gate',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.nfc-tags.toggle-status', $tag));

        $response->assertRedirect();
        $tag->refresh();
        $this->assertEquals('inactive', $tag->status);

        // Toggle again
        $this->actingAs($this->admin, 'web')
            ->post(route('admin.nfc-tags.toggle-status', $tag));

        $tag->refresh();
        $this->assertEquals('active', $tag->status);
    }
}
