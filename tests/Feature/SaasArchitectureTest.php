<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaasArchitectureTest extends TestCase
{
    use RefreshDatabase;

    public function test_dynamic_qr_code_auto_generates_vp_prefix_code(): void
    {
        $client = Client::create([
            'name' => 'Acme Corporation',
            'email' => 'acme@example.com',
            'city' => 'New York',
            'phone' => '+1 555 123 4567',
            'project_name' => 'Corporate Branding',
            'status' => 'active',
        ]);

        $qr = DynamicQrCode::create([
            'client_id' => $client->id,
            'label' => 'Automated Test QR',
            'target_url' => 'https://example.com/target',
            'template_type' => 'google_review',
        ]);

        $this->assertNotNull($qr->code);
        $this->assertStringStartsWith('VP-', $qr->code);
        $this->assertEquals(9, strlen($qr->code)); // "VP-" + 6 alphanumeric chars = 9
    }

    public function test_client_relationships_and_history_logging(): void
    {
        $client = Client::create([
            'name' => 'Test Hotel & Spa',
            'email' => 'hotel@example.com',
            'city' => 'Casablanca',
            'phone' => '+212 522 000000',
            'project_name' => 'Coastal Luxury',
            'status' => 'active',
            'notes' => 'Testing observer logging',
        ]);

        // Observer automatically creates history entry
        $this->assertTrue($client->clientHistories()->where('action_type', 'client_created')->exists());

        // Test DigitalCard relation and observer history
        $card = DigitalCard::create([
            'client_id' => $client->id,
            'title' => 'Director Card',
            'template_id' => 'modern',
            'theme_config' => ['primary' => '#10b981'],
            'content_blocks' => ['bio' => 'Hospitality Director'],
        ]);

        $this->assertTrue($client->digitalCards->contains($card));
        $this->assertTrue($client->clientHistories()->where('action_type', 'digital_card_created')->exists());

        // Test DynamicQrCode relation and observer history
        $qr = DynamicQrCode::create([
            'client_id' => $client->id,
            'label' => 'Restaurant Menu',
            'target_url' => 'https://example.com/menu',
            'template_type' => 'menu',
        ]);

        $this->assertTrue($client->dynamicQrCodes->contains($qr));
        $this->assertTrue($client->clientHistories()->where('action_type', 'dynamic_qr_created')->exists());

        // Test NfcTag relation and observer history
        $nfc = NfcTag::create([
            'client_id' => $client->id,
            'tag_uid' => NfcTag::generateUniqueUid(),
            'label' => 'VIP Card Tag',
            'destination_url' => 'https://example.com/vip',
        ]);

        $this->assertTrue($client->nfcTags->contains($nfc));
        $this->assertTrue($client->clientHistories()->where('action_type', 'nfc_tag_created')->exists());
    }

    public function test_polymorphic_scan_logs_for_qr_and_nfc(): void
    {
        $client = Client::create([
            'name' => 'Global Retailer',
            'email' => 'retail@example.com',
            'city' => 'London',
            'phone' => '+44 20 7946 0912',
            'project_name' => 'Store Fronts',
            'status' => 'active',
        ]);

        $qr = DynamicQrCode::create([
            'client_id' => $client->id,
            'label' => 'Menu QR Code',
            'target_url' => 'https://example.com/menu',
            'template_type' => 'menu',
        ]);

        $scanQr = $qr->recordScan('192.168.1.100', 'Mobile Safari');
        $this->assertEquals(1, $qr->fresh()->scans_count);
        $this->assertEquals('DynamicQrCode', $scanQr->trackable_type);
        $this->assertInstanceOf(DynamicQrCode::class, $scanQr->trackable);

        $nfc = NfcTag::create([
            'client_id' => $client->id,
            'tag_uid' => 'NFC-TEST-99',
            'label' => 'Room Key Tag',
            'destination_url' => 'https://example.com/room',
        ]);

        $scanNfc = $nfc->recordScan('10.0.0.50', 'Android Chrome');
        $this->assertEquals(1, $nfc->fresh()->scans_count);
        $this->assertEquals('NfcTag', $scanNfc->trackable_type);
        $this->assertInstanceOf(NfcTag::class, $scanNfc->trackable);
    }
}
