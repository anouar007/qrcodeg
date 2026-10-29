<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DynamicQrCode;
use App\Services\DynamicQrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DynamicQrCodeController extends Controller
{
    public function __construct(
        protected DynamicQrCodeService $qrService
    ) {}

    /**
     * Permanent Redirection Controller
     * Resolves the active target_url, increments scans_count, logs ScanLog (IP, user agent, timestamp), and performs a 302 redirect.
     */
    public function redirect(Request $request, string $code): RedirectResponse|Response
    {
        $qr = DynamicQrCode::where('code', $code)->first();

        if (! $qr) {
            return response()->view('qr.unavailable', [
                'title' => 'QR Code Not Found',
                'message' => "The Dynamic QR Code with identifier '{$code}' does not exist or has been removed.",
            ], 404);
        }

        if ($qr->status !== 'active') {
            return response()->view('qr.unavailable', [
                'title' => 'QR Code Suspended',
                'message' => "This dynamic QR code ({$qr->code}) is currently marked as {$qr->status}.",
            ], 200);
        }

        // Increment scans_count and record ScanLog with IP, user agent, and timestamp
        $qr->recordScan(
            ipAddress: $request->ip(),
            userAgent: $request->userAgent()
        );

        $targetUrl = trim($qr->target_url);

        // Ensure scheme exists for external redirection
        if (! preg_match('#^[a-zA-Z][a-zA-Z0-9+.-]*://#', $targetUrl) && ! str_starts_with($targetUrl, 'tel:') && ! str_starts_with($targetUrl, 'mailto:')) {
            $targetUrl = 'https://'.$targetUrl;
        }

        return redirect()->away($targetUrl, 302);
    }

    /**
     * Dashboard List & Target Management
     */
    public function index(Request $request)
    {
        $query = DynamicQrCode::with(['client', 'scanLogs']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('name', 'like', "%{$search}%")->orWhere('project_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('template_type')) {
            $query->where('template_type', $request->template_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'scans_high' => $query->orderByDesc('scans_count'),
            'scans_low' => $query->orderBy('scans_count'),
            'label' => $query->orderBy('label'),
            'code' => $query->orderBy('code'),
            default => $query->latest(),
        };

        $qrCodes = $query->paginate(20)->withQueryString();
        $clients = Client::orderBy('name')->get();

        $metrics = [
            'total' => DynamicQrCode::count(),
            'scans' => (int) DynamicQrCode::sum('scans_count'),
            'active' => DynamicQrCode::where('status', 'active')->count(),
            'inactive' => DynamicQrCode::where('status', '!=', 'active')->count(),
        ];

        return view('admin.dynamic_qrs.index', compact('qrCodes', 'clients', 'metrics'));
    }

    /**
     * Show creation view (supports both single & bulk forms)
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $sampleQrSvg = (string) @QrCode::format('svg')->size(300)->margin(0)->generate('https://example.com/demo');

        return view('admin.dynamic_qrs.create', compact('clients', 'sampleQrSvg'));
    }

    /**
     * Store a single Dynamic QR Code
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'label' => 'required|string|max:150',
            'target_url' => 'required|string|max:1000',
            'template_type' => 'required|in:google_review,social_media,menu',
            'code' => [
                'nullable',
                'string',
                'max:32',
                'regex:/^VP-[A-Za-z0-9_-]+$/',
                'unique:dynamic_qr_codes,code',
            ],
            'status' => 'nullable|in:active,inactive,archived',
        ]);

        $code = ! empty($validated['code'])
            ? strtoupper(trim($validated['code']))
            : DynamicQrCode::generateUniqueCode();

        $qr = DynamicQrCode::create([
            'client_id' => $validated['client_id'] ?? null,
            'code' => $code,
            'label' => $validated['label'],
            'target_url' => $validated['target_url'],
            'template_type' => $validated['template_type'],
            'scans_count' => 0,
            'status' => $validated['status'] ?? 'active',
        ]);

        return redirect()->route('admin.dynamic-qrs.index')
            ->with('success', "Dynamic QR Code {$qr->code} ('{$qr->label}') created successfully.");
    }

    /**
     * Bulk-generate dynamic QR codes (e.g., 5, 10, 50 at once) assigned to a client
     */
    public function bulkStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'quantity' => 'required|integer|min:1|max:100',
            'label_prefix' => 'required|string|max:100',
            'target_url' => 'required|string|max:1000',
            'template_type' => 'required|in:google_review,social_media,menu',
            'code_style' => 'nullable|in:unique,sequential,numeric',
            'status' => 'nullable|in:active,inactive,archived',
        ]);

        $quantity = (int) $validated['quantity'];
        $style = $validated['code_style'] ?? 'unique';
        $createdCount = 0;

        DB::transaction(function () use ($validated, $quantity, $style, &$createdCount) {
            $baseSeq = DynamicQrCode::where('code', 'like', 'VP-%')->count() + 1;

            for ($i = 1; $i <= $quantity; $i++) {
                $code = match ($style) {
                    'sequential' => DynamicQrCode::generateSequentialCode($baseSeq + $i - 1),
                    'numeric' => DynamicQrCode::generateUniqueCode(6, true),
                    default => DynamicQrCode::generateUniqueCode(6, false),
                };

                $label = $quantity === 1
                    ? $validated['label_prefix']
                    : "{$validated['label_prefix']} #".str_pad((string) $i, 2, '0', STR_PAD_LEFT);

                DynamicQrCode::create([
                    'client_id' => $validated['client_id'] ?? null,
                    'code' => $code,
                    'label' => $label,
                    'target_url' => $validated['target_url'],
                    'template_type' => $validated['template_type'],
                    'scans_count' => 0,
                    'status' => $validated['status'] ?? 'active',
                ]);

                $createdCount++;
            }
        });

        $clientName = ! empty($validated['client_id'])
            ? Client::find($validated['client_id'])?->name
            : 'general pool';

        return redirect()->route('admin.dynamic-qrs.index')
            ->with('success', "Successfully bulk-generated {$createdCount} Dynamic QR codes (format VP-XXXXXX) for {$clientName}.");
    }

    /**
     * Interactive Studio: Bulk Dynamic QR Generation with Custom Design Template & Direct Multi-Page PDF Download
     */
    public function generatePdfBatch(Request $request): Response|RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'quantity' => 'required|integer|min:1|max:100',
            'label_prefix' => 'required|string|max:100',
            'target_url' => 'required|string|max:1000',
            'code_style' => 'nullable|in:unique,sequential,numeric',
            'status' => 'nullable|in:active,inactive',

            // Canvas Dimensions & Units
            'canvas_unit' => 'required|in:px,cm,mm',
            'canvas_width' => 'required|numeric|min:1|max:10000',
            'canvas_height' => 'required|numeric|min:1|max:10000',

            // Template Upload / Preset
            'template_image' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg|extensions:jpeg,png,jpg,webp,svg|max:12288',
            'existing_template_image' => 'nullable|string|max:255',

            // QR Overlay Coordinates & Style
            'qr_x' => 'nullable|numeric|min:0|max:100',
            'qr_y' => 'nullable|numeric|min:0|max:100',
            'qr_size' => 'nullable|numeric|min:5|max:100',
            'qr_fg' => 'nullable|string|max:10',
            'qr_bg' => 'nullable|string|max:15',

            // Unique Code Coordinates & Style
            'show_code' => 'nullable',
            'code_x' => 'nullable|numeric|min:0|max:100',
            'code_y' => 'nullable|numeric|min:0|max:100',
            'code_font_size' => 'nullable|numeric|min:6|max:120',
            'code_color' => 'nullable|string|max:10',
            'code_bg' => 'nullable|string|max:20',
            'code_font_weight' => 'nullable|in:bold,normal,600,700,800',
            'code_font_family' => 'nullable|in:monospace,sans-serif,serif',
            'code_letter_spacing' => 'nullable|numeric|min:0|max:20',
            'stage_width' => 'nullable|numeric|min:50|max:4000',
            'stage_height' => 'nullable|numeric|min:50|max:4000',
        ]);

        $quantity = (int) $validated['quantity'];
        $style = $validated['code_style'] ?? 'unique';

        // 1. Resolve Background Design Image
        $templateStoragePath = null;
        $absoluteTemplatePath = null;
        $isExplicitlyBlank = ($validated['existing_template_image'] ?? '') === 'none';

        if (! $isExplicitlyBlank) {
            if ($request->hasFile('template_image')) {
                $templateStoragePath = $request->file('template_image')->store('templates', 'public');
                $absoluteTemplatePath = storage_path('app/public/'.$templateStoragePath);
            } elseif (! empty($validated['existing_template_image'])) {
                $rawPath = trim($validated['existing_template_image']);
                if (file_exists(public_path($rawPath))) {
                    $absoluteTemplatePath = public_path($rawPath);
                    $templateStoragePath = $rawPath;
                } elseif (file_exists(storage_path('app/public/'.$rawPath))) {
                    $absoluteTemplatePath = storage_path('app/public/'.$rawPath);
                    $templateStoragePath = $rawPath;
                }
            }

            // Fallback to sample plaque only if not explicitly set to blank
            if (! $absoluteTemplatePath && ! isset($validated['existing_template_image']) && file_exists(public_path('images/templates/google_review_plaque.jpg'))) {
                $absoluteTemplatePath = public_path('images/templates/google_review_plaque.jpg');
                $templateStoragePath = 'images/templates/google_review_plaque.jpg';
            }
        }

        // 2. Assemble Layout Design Configuration
        $designConfig = [
            'canvas_unit' => $validated['canvas_unit'],
            'canvas_width' => (float) $validated['canvas_width'],
            'canvas_height' => (float) $validated['canvas_height'],
            'stage_width' => (float) ($validated['stage_width'] ?? 460),
            'stage_height' => (float) ($validated['stage_height'] ?? 460),
            'qr_x' => (float) ($validated['qr_x'] ?? 50),
            'qr_y' => (float) ($validated['qr_y'] ?? 47.5),
            'qr_size' => (float) ($validated['qr_size'] ?? 35),
            'qr_fg' => $validated['qr_fg'] ?? '#000000',
            'qr_bg' => $validated['qr_bg'] ?? '#ffffff',
            'show_code' => $request->boolean('show_code', true),
            'code_x' => (float) ($validated['code_x'] ?? 50),
            'code_y' => (float) ($validated['code_y'] ?? 74),
            'code_font_size' => (float) ($validated['code_font_size'] ?? 14),
            'code_color' => $validated['code_color'] ?? '#000000',
            'code_bg' => $validated['code_bg'] ?? 'transparent',
            'code_font_weight' => $validated['code_font_weight'] ?? 'bold',
            'code_font_family' => $validated['code_font_family'] ?? 'monospace',
            'code_letter_spacing' => (float) ($validated['code_letter_spacing'] ?? 1),
        ];

        // 3. Create Dynamic QR Codes in Database
        $createdCodes = [];

        DB::transaction(function () use ($validated, $quantity, $style, $designConfig, $templateStoragePath, &$createdCodes) {
            $baseSeq = DynamicQrCode::where('code', 'like', 'VP-%')->count() + 1;

            for ($i = 1; $i <= $quantity; $i++) {
                $code = match ($style) {
                    'sequential' => DynamicQrCode::generateSequentialCode($baseSeq + $i - 1),
                    'numeric' => DynamicQrCode::generateUniqueCode(6, true),
                    default => DynamicQrCode::generateUniqueCode(6, false),
                };

                $label = $quantity === 1
                    ? $validated['label_prefix']
                    : "{$validated['label_prefix']} #".str_pad((string) $i, 2, '0', STR_PAD_LEFT);

                $created = DynamicQrCode::create([
                    'client_id' => $validated['client_id'] ?? null,
                    'code' => $code,
                    'label' => $label,
                    'target_url' => $validated['target_url'],
                    'template_type' => 'custom',
                    'template_image' => $templateStoragePath,
                    'design_config' => $designConfig,
                    'scans_count' => 0,
                    'status' => $validated['status'] ?? 'active',
                ]);

                $createdCodes[] = $created;
            }
        });

        // 4. Generate Multi-Page Printable PDF
        $pdf = $this->qrService->generateBatchDesignPdf($createdCodes, $designConfig, $absoluteTemplatePath);

        $safePrefix = preg_replace('/[^a-zA-Z0-9_-]/', '_', $validated['label_prefix'] ?: 'dynamic_qrs');
        $filename = "{$safePrefix}_{$quantity}_pages_".date('Ymd_His').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show Dynamic QR code details & scan history
     */
    public function show(DynamicQrCode $dynamicQr)
    {
        $dynamicQr->load(['client', 'scanLogs' => fn ($q) => $q->latest('scanned_at')->take(50)]);
        $svgPreview = $this->qrService->renderSvg($dynamicQr, 280);

        return view('admin.dynamic_qrs.show', compact('dynamicQr', 'svgPreview'));
    }

    /**
     * Edit view
     */
    public function edit(DynamicQrCode $dynamicQr)
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $svgPreview = $this->qrService->renderSvg($dynamicQr, 240);

        return view('admin.dynamic_qrs.edit', compact('dynamicQr', 'clients', 'svgPreview'));
    }

    /**
     * Update Dynamic QR code
     */
    public function update(Request $request, DynamicQrCode $dynamicQr): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'label' => 'required|string|max:150',
            'target_url' => 'required|string|max:1000',
            'template_type' => 'required|in:google_review,social_media,menu',
            'status' => 'required|in:active,inactive,archived',
        ]);

        $dynamicQr->update($validated);

        return redirect()->route('admin.dynamic-qrs.show', $dynamicQr)
            ->with('success', "Dynamic QR Code {$dynamicQr->code} updated successfully.");
    }

    /**
     * Instant Target Management
     * Allows updating the target_url without re-generating or re-printing the QR code
     */
    public function updateTarget(Request $request, DynamicQrCode $dynamicQr): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'target_url' => 'required|string|max:1000',
        ]);

        $oldUrl = $dynamicQr->target_url;
        $dynamicQr->update([
            'target_url' => $validated['target_url'],
        ]);

        $message = "Target destination updated to {$dynamicQr->target_url}. The printed QR ({$dynamicQr->code}) remains 100% active and unchanged.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'code' => $dynamicQr->code,
                'target_url' => $dynamicQr->target_url,
                'old_target_url' => $oldUrl,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Printable PDF Card Export (Google Review, Social Media, Menu)
     */
    public function exportPdf(Request $request, DynamicQrCode $dynamicQr): Response
    {
        $template = $request->input('template', $dynamicQr->template_type ?: 'google_review');
        $dimension = $request->input('dimension', '85x55'); // '85x55' or 'a6'

        $pdf = $this->qrService->generateCardPdf($dynamicQr, $template, $dimension);

        $safeLabel = preg_replace('/[^a-zA-Z0-9_-]/', '_', $dynamicQr->label ?: 'card');
        $filename = "{$dynamicQr->code}_{$template}_{$dimension}_{$safeLabel}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Raw vector SVG QR download or inline view
     */
    public function downloadSvg(Request $request, DynamicQrCode $dynamicQr): Response
    {
        $size = max(50, min(1200, (int) $request->input('size', 600)));
        $svg = $this->qrService->renderSvg($dynamicQr, $size);
        $filename = "{$dynamicQr->code}_vector.svg";

        $disposition = $request->boolean('inline') ? 'inline' : "attachment; filename=\"{$filename}\"";

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => $disposition,
        ]);
    }

    /**
     * High-resolution PNG QR download or inline view
     */
    public function downloadPng(Request $request, DynamicQrCode $dynamicQr): Response
    {
        $size = max(50, min(1200, (int) $request->input('size', 600)));
        $png = $this->qrService->renderPng($dynamicQr, $size);
        $filename = "{$dynamicQr->code}_hires.png";

        $disposition = $request->boolean('inline') ? 'inline' : "attachment; filename=\"{$filename}\"";

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => $disposition,
        ]);
    }

    /**
     * Toggle active/inactive status quickly
     */
    public function toggleStatus(Request $request, DynamicQrCode $dynamicQr): JsonResponse|RedirectResponse
    {
        $newStatus = ($dynamicQr->status === 'active') ? 'inactive' : 'active';
        $dynamicQr->update(['status' => $newStatus]);

        $statusLabel = ($newStatus === 'active') ? 'Actif' : 'Inactif';
        $message = "Statut du code {$dynamicQr->code} mis à jour : {$statusLabel}.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'status_label' => $statusLabel,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Delete Dynamic QR Code
     */
    public function destroy(DynamicQrCode $dynamicQr): RedirectResponse
    {
        $code = $dynamicQr->code;
        $dynamicQr->delete();

        return redirect()->route('admin.dynamic-qrs.index')
            ->with('success', "Dynamic QR Code {$code} deleted.");
    }
}
