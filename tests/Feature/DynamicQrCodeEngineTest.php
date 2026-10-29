<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientHistory;
use App\Models\DynamicQrCode;
use App\Models\ScanLog;
use App\Models\User;
use App\Services\DynamicQrCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DynamicQrCodeEngineTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@dynqr.test',
            'status' => 'active',
        ]);

        $this->client = Client::create([
            'name' => 'Marrakech Palace Resort',
            'email' => 'contact@marrakechpalace.ma',
            'project_name' => 'Luxury Hospitality',
            'city' => 'Marrakech',
            'phone' => '+212524430000',
            'status' => 'active',
        ]);
    }

    /**
     * 1. Test automatic VP-XXXXXX code generation & encoded URL route
     */
    public function test_automatic_vp_code_generation_and_encoded_url(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'label' => 'Main Reception Review Card',
            'target_url' => 'https://g.page/r/marrakech-palace',
            'template_type' => 'google_review',
        ]);

        $this->assertNotNull($qr->code);
        $this->assertStringStartsWith('VP-', $qr->code);
        $this->assertMatchesRegularExpression('/^VP-[A-Za-z0-9_-]{6,}$/', $qr->code);

        // Encoded URL must match permanent redirect route
        $expectedUrl = route('qr.redirect', ['code' => $qr->code]);
        $this->assertEquals($expectedUrl, $qr->encoded_url);
    }

    /**
     * 2. Test 302 Redirection, scans_count increment, and polymorphic ScanLog entry
     */
    public function test_redirection_controller_resolves_target_increments_scan_and_logs_telemetry(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-984210',
            'label' => 'Poolside Bar Menu',
            'target_url' => 'https://marrakechpalace.ma/pool-menu',
            'template_type' => 'menu',
            'scans_count' => 0,
            'status' => 'active',
        ]);

        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '196.200.150.30',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1',
        ])->get(route('qr.redirect', ['code' => 'VP-984210']));

        // Assert 302 redirect
        $response->assertStatus(302);
        $response->assertRedirect('https://marrakechpalace.ma/pool-menu');

        // Assert scans_count incremented
        $qr->refresh();
        $this->assertEquals(1, $qr->scans_count);

        // Assert polymorphic ScanLog logged with IP, user agent, timestamp
        $scanLog = ScanLog::where('trackable_type', 'DynamicQrCode')
            ->where('trackable_id', $qr->id)
            ->first();

        $this->assertNotNull($scanLog);
        $this->assertEquals('196.200.150.30', $scanLog->ip_address);
        $this->assertStringContainsString('iPhone', $scanLog->user_agent);
        $this->assertNotNull($scanLog->scanned_at);
        $this->assertEquals($qr->id, $scanLog->trackable->id);
    }

    /**
     * 3. Test redirection handles target URLs without http/https protocol prefix
     */
    public function test_redirection_normalizes_missing_protocol_scheme(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-123456',
            'label' => 'Social Hub',
            'target_url' => 'instagram.com/palaceresort',
            'template_type' => 'social_media',
            'status' => 'active',
        ]);

        $response = $this->get(route('qr.redirect', ['code' => 'VP-123456']));
        $response->assertStatus(302);
        $response->assertRedirect('https://instagram.com/palaceresort');
    }

    /**
     * 4. Test suspended QR code returns unavailable view without redirection
     */
    public function test_inactive_qr_does_not_redirect(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-SUSPEND',
            'label' => 'Winter Promo (Expired)',
            'target_url' => 'https://example.com/expired',
            'template_type' => 'google_review',
            'status' => 'inactive',
        ]);

        $response = $this->get(route('qr.redirect', ['code' => 'VP-SUSPEND']));
        $response->assertStatus(200);
        $response->assertSee('QR Code Suspended');
    }

    /**
     * 5. Test instant target management without re-generating or re-printing the QR code
     */
    public function test_instant_target_destination_update(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-PERMANENT',
            'label' => 'Lobby Review Stand',
            'target_url' => 'https://initial-review.com',
            'template_type' => 'google_review',
            'status' => 'active',
        ]);

        $initialCode = $qr->code;
        $initialUrl = $qr->encoded_url;

        // Perform instant destination update via authenticated admin endpoint
        $response = $this->actingAs($this->admin, 'web')
            ->put(route('admin.dynamic-qrs.target', $qr), [
                'target_url' => 'https://new-instant-review-destination.com/qr',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $qr->refresh();
        $this->assertEquals('https://new-instant-review-destination.com/qr', $qr->target_url);
        // Code and permanent encoded URL remain completely identical!
        $this->assertEquals($initialCode, $qr->code);
        $this->assertEquals($initialUrl, $qr->encoded_url);

        // Verify ClientHistory audit trail
        $history = ClientHistory::where('client_id', $this->client->id)
            ->where('description', 'like', '%QR VP-PERMANENT redirected to URL https://new-instant-review-destination.com/qr%')
            ->first();
        $this->assertNotNull($history);

        // Test immediate live redirection hits the new URL
        $redirectResponse = $this->get(route('qr.redirect', ['code' => $qr->code]));
        $redirectResponse->assertStatus(302);
        $redirectResponse->assertRedirect('https://new-instant-review-destination.com/qr');
    }

    /**
     * 6. Test AJAX instant target destination update
     */
    public function test_instant_target_destination_update_via_ajax_json(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-AJAX01',
            'label' => 'Bar QR Code',
            'target_url' => 'https://old-bar.com',
            'template_type' => 'menu',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->putJson(route('admin.dynamic-qrs.target', $qr), [
                'target_url' => 'https://new-cocktails.com/menu',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'code' => 'VP-AJAX01',
            'target_url' => 'https://new-cocktails.com/menu',
            'old_target_url' => 'https://old-bar.com',
        ]);

        $qr->refresh();
        $this->assertEquals('https://new-cocktails.com/menu', $qr->target_url);
    }

    /**
     * 7. Test single QR code store action via Dashboard controller
     */
    public function test_single_qr_code_store_action(): void
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.dynamic-qrs.store'), [
                'client_id' => $this->client->id,
                'label' => 'Terrace Table #5',
                'target_url' => 'https://marrakechpalace.ma/terrace-menu',
                'template_type' => 'menu',
            ]);

        $response->assertRedirect(route('admin.dynamic-qrs.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dynamic_qr_codes', [
            'client_id' => $this->client->id,
            'label' => 'Terrace Table #5',
            'target_url' => 'https://marrakechpalace.ma/terrace-menu',
            'template_type' => 'menu',
        ]);

        $qr = DynamicQrCode::where('label', 'Terrace Table #5')->first();
        $this->assertNotNull($qr);
        $this->assertStringStartsWith('VP-', $qr->code);
    }

    /**
     * 8. Test bulk generation of dynamic QR codes (e.g. 5, 10, 50 at once)
     */
    public function test_bulk_generation_creates_unique_vp_codes_assigned_to_client(): void
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.dynamic-qrs.bulk'), [
                'client_id' => $this->client->id,
                'quantity' => 10,
                'label_prefix' => 'Bistro Table',
                'target_url' => 'https://marrakechpalace.ma/menu',
                'template_type' => 'menu',
                'code_style' => 'unique',
            ]);

        $response->assertRedirect(route('admin.dynamic-qrs.index'));
        $response->assertSessionHas('success');

        $createdQrs = DynamicQrCode::where('client_id', $this->client->id)
            ->where('label', 'like', 'Bistro Table%')
            ->get();

        $this->assertCount(10, $createdQrs);

        // All codes must be unique and start with VP-
        $codes = $createdQrs->pluck('code')->all();
        $this->assertCount(10, array_unique($codes));

        foreach ($codes as $code) {
            $this->assertStringStartsWith('VP-', $code);
        }
    }

    /**
     * 9. Test bulk generation with sequential serial numbering
     */
    public function test_bulk_generation_with_sequential_numbering(): void
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.dynamic-qrs.bulk'), [
                'client_id' => $this->client->id,
                'quantity' => 5,
                'label_prefix' => 'VIP Lounge',
                'target_url' => 'https://marrakechpalace.ma/vip',
                'template_type' => 'social_media',
                'code_style' => 'sequential',
            ]);

        $response->assertRedirect(route('admin.dynamic-qrs.index'));

        $qrs = DynamicQrCode::where('client_id', $this->client->id)
            ->where('label', 'like', 'VIP Lounge%')
            ->get();

        $this->assertCount(5, $qrs);
        foreach ($qrs as $qr) {
            $this->assertMatchesRegularExpression('/^VP-\d{6}$/', $qr->code);
        }
    }

    /**
     * 10. Test Printable PDF Card Export for all three distinct designs
     */
    public function test_pdf_card_export_for_all_three_designs(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-777888',
            'label' => 'Presidential Suite Card',
            'target_url' => 'https://marrakechpalace.ma/suite',
            'template_type' => 'google_review',
        ]);

        $designs = ['google_review', 'social_media', 'menu'];

        foreach ($designs as $design) {
            // Test 85mm x 55mm card export
            $response85x55 = $this->actingAs($this->admin, 'web')
                ->get(route('admin.dynamic-qrs.pdf', [
                    'dynamicQr' => $qr->id,
                    'template' => $design,
                    'dimension' => '85x55',
                ]));

            $response85x55->assertStatus(200);
            $response85x55->assertHeader('content-type', 'application/pdf');
            $this->assertStringContainsString($qr->code, $response85x55->headers->get('content-disposition'));
            $this->assertNotEmpty($response85x55->getContent());

            // Test A6 card export
            $responseA6 = $this->actingAs($this->admin, 'web')
                ->get(route('admin.dynamic-qrs.pdf', [
                    'dynamicQr' => $qr->id,
                    'template' => $design,
                    'dimension' => 'a6',
                ]));

            $responseA6->assertStatus(200);
            $responseA6->assertHeader('content-type', 'application/pdf');
            $this->assertNotEmpty($responseA6->getContent());
        }
    }

    /**
     * 11. Test SVG & PNG raw downloads
     */
    public function test_svg_and_png_generator_and_download_actions(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-VECTOR',
            'label' => 'High-Res Asset Test',
            'target_url' => 'https://marrakechpalace.ma/assets',
            'template_type' => 'google_review',
        ]);

        // SVG download
        $svgResponse = $this->actingAs($this->admin, 'web')
            ->get(route('admin.dynamic-qrs.svg', $qr));

        $svgResponse->assertStatus(200);
        $svgResponse->assertHeader('content-type', 'image/svg+xml');
        $this->assertStringContainsString('<svg', $svgResponse->getContent());

        // PNG download
        $pngResponse = $this->actingAs($this->admin, 'web')
            ->get(route('admin.dynamic-qrs.png', $qr));

        $pngResponse->assertStatus(200);
        $pngResponse->assertHeader('content-type', 'image/png');
        $this->assertNotEmpty($pngResponse->getContent());
    }

    /**
     * 12. Test DynamicQrCodeService directly
     */
    public function test_dynamic_qr_service_renders_valid_svg_and_png_data_uris(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-SERVICE',
            'label' => 'Service Unit Check',
            'target_url' => 'https://example.com/check',
            'template_type' => 'menu',
        ]);

        $service = app(DynamicQrCodeService::class);

        $svg = $service->renderSvg($qr, 200);
        $this->assertStringContainsString('<svg', $svg);

        $svgDataUri = $service->renderSvgDataUri($qr, 200);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $svgDataUri);

        $pngDataUri = $service->renderPngDataUri($qr, 200);
        $this->assertStringStartsWith('data:image/png;base64,', $pngDataUri);
    }

    /**
     * 13. Test Studio generator page renders with dimension and visual drag & drop controls
     */
    public function test_dynamic_qr_generator_studio_page_renders(): void
    {
        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.dynamic-qrs.create'));

        $response->assertStatus(200);
        $response->assertSee('Générateur de QR Codes Dynamiques');
        $response->assertSee('canvasUnitSelect', false);
        $response->assertSee('canvasWidthInput', false);
        $response->assertSee('canvasHeightInput', false);
        $response->assertSee('draggableQr', false);
        $response->assertSee('draggableCode', false);
        $response->assertSee('batchPdfStudioForm', false);
        $response->assertSee('sliderCodeLetterSpacing', false);
        $response->assertSee('btnAlignEverything', false);
        $response->assertSee('canvasRatioBadge', false);
        $response->assertSee('imgDetectedDimBadge', false);
    }

    /**
     * 14. Test bulk generation with custom dimensions, drag & drop coordinates and direct multi-page PDF download
     */
    public function test_generate_pdf_batch_creates_codes_and_downloads_multipage_pdf(): void
    {
        $payload = [
            'client_id' => $this->client->id,
            'quantity' => 3,
            'label_prefix' => 'VIP Table Plaque',
            'target_url' => 'https://marrakechpalace.ma/review',
            'code_style' => 'unique',
            'status' => 'active',
            'canvas_unit' => 'cm',
            'canvas_width' => 30,
            'canvas_height' => 30,
            'qr_x' => 50,
            'qr_y' => 45,
            'qr_size' => 40,
            'qr_fg' => '#000000',
            'qr_bg' => '#ffffff',
            'show_code' => 1,
            'code_x' => 50,
            'code_y' => 80,
            'code_font_size' => 16,
            'code_color' => '#6320ee',
            'code_bg' => 'transparent',
            'code_font_weight' => 'bold',
            'code_font_family' => 'monospace',
            'code_letter_spacing' => 2,
        ];

        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.dynamic-qrs.generate-pdf'), $payload);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        // Verify 3 codes were created in database with VP- prefix
        $this->assertEquals(3, DynamicQrCode::where('label', 'like', 'VIP Table Plaque%')->count());

        $firstCreated = DynamicQrCode::where('label', 'like', 'VIP Table Plaque%')->first();
        $this->assertNotNull($firstCreated);
        $this->assertStringStartsWith('VP-', $firstCreated->code);
        $this->assertEquals('custom', $firstCreated->template_type);
        $this->assertEquals(30, $firstCreated->design_config['canvas_width']);
        $this->assertEquals('cm', $firstCreated->design_config['canvas_unit']);
        $this->assertEquals(40, $firstCreated->design_config['qr_size']);
    }

    /**
     * 14. Test toggle status endpoint
     */
    public function test_toggle_status_endpoint_updates_status_and_returns_json(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-TOGGLE1',
            'label' => 'Status Toggle Test',
            'target_url' => 'https://example.com/test',
            'template_type' => 'menu',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->postJson(route('admin.dynamic-qrs.toggle-status', $qr));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'inactive',
        ]);
        $this->assertEquals('inactive', $qr->fresh()->status);

        // Toggle back to active
        $response2 = $this->actingAs($this->admin, 'web')
            ->postJson(route('admin.dynamic-qrs.toggle-status', $qr));

        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'status' => 'active',
        ]);
        $this->assertEquals('active', $qr->fresh()->status);
    }

    /**
     * 15. Test SVG and PNG inline preview
     */
    public function test_svg_and_png_inline_preview_headers(): void
    {
        $qr = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-INLINE',
            'label' => 'Inline Thumbnail Test',
            'target_url' => 'https://example.com/inline',
            'template_type' => 'google_review',
        ]);

        $svgResponse = $this->actingAs($this->admin, 'web')
            ->get(route('admin.dynamic-qrs.svg', [$qr, 'inline' => 1, 'size' => 100]));

        $svgResponse->assertStatus(200);
        $svgResponse->assertHeader('content-type', 'image/svg+xml');
        $this->assertEquals('inline', $svgResponse->headers->get('content-disposition'));

        $pngResponse = $this->actingAs($this->admin, 'web')
            ->get(route('admin.dynamic-qrs.png', [$qr, 'inline' => 1, 'size' => 100]));

        $pngResponse->assertStatus(200);
        $pngResponse->assertHeader('content-type', 'image/png');
        $this->assertEquals('inline', $pngResponse->headers->get('content-disposition'));
    }

    /**
     * 16. Test Dynamic QR index page renders pro interface, CSV export and bulk actions
     */
    public function test_index_pro_interface_csv_export_and_bulk_actions(): void
    {
        $qr1 = DynamicQrCode::create([
            'client_id' => $this->client->id,
            'code' => 'VP-PRO001',
            'label' => 'Pro Reception Stand',
            'target_url' => 'https://example.com/pro1',
            'template_type' => 'google_review',
            'status' => 'active',
        ]);

        $qr2 = DynamicQrCode::create([
            'client_id' => null,
            'code' => 'VP-PRO002',
            'label' => 'Unassigned Stand',
            'target_url' => 'https://example.com/pro2',
            'template_type' => 'menu',
            'status' => 'active',
        ]);

        // Index renders with pro elements
        $indexRes = $this->actingAs($this->admin, 'web')
            ->get(route('admin.dynamic-qrs.index'));

        $indexRes->assertStatus(200);
        $indexRes->assertSee('VP-PRO001');
        $indexRes->assertSee('VP-PRO002');
        $indexRes->assertSee('dynBulkActionBar', false);
        $indexRes->assertSee('qrLightboxModal', false);

        // CSV Export
        $csvRes = $this->actingAs($this->admin, 'web')
            ->get(route('admin.dynamic-qrs.export'));

        $csvRes->assertStatus(200);
        $this->assertStringContainsString('VP-PRO001', $csvRes->getContent());
        $this->assertStringContainsString('Pro Reception Stand', $csvRes->getContent());

        // Bulk Action: Deactivate both
        $bulkRes = $this->actingAs($this->admin, 'web')
            ->postJson(route('admin.dynamic-qrs.bulk-action'), [
                'ids' => [$qr1->id, $qr2->id],
                'action' => 'deactivate',
            ]);

        $bulkRes->assertStatus(200)->assertJson(['success' => true, 'count' => 2]);
        $this->assertEquals('inactive', $qr1->fresh()->status);
        $this->assertEquals('inactive', $qr2->fresh()->status);

        // Bulk Action: Update Target URL
        $bulkTargetRes = $this->actingAs($this->admin, 'web')
            ->postJson(route('admin.dynamic-qrs.bulk-action'), [
                'ids' => [$qr1->id, $qr2->id],
                'action' => 'update_target',
                'target_url' => 'https://example.com/unified-campaign',
            ]);

        $bulkTargetRes->assertStatus(200)->assertJson(['success' => true]);
        $this->assertEquals('https://example.com/unified-campaign', $qr1->fresh()->target_url);
        $this->assertEquals('https://example.com/unified-campaign', $qr2->fresh()->target_url);
    }
}
