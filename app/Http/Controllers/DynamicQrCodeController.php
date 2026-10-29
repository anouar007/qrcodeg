<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DynamicQrCode;
use App\Models\FileUpload;
use App\Services\DynamicQrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            if ($request->client_id === 'unassigned') {
                $query->whereNull('client_id');
            } else {
                $query->where('client_id', $request->client_id);
            }
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

        $rawPerPage = $request->input('per_page', '20');
        if ($rawPerPage === 'all') {
            $perPageCount = max(1, (clone $query)->count());
            $perPage = 'all';
        } else {
            $allowedPerPage = [12, 20, 24, 48, 100];
            $parsed = (int) $rawPerPage;
            $perPageCount = in_array($parsed, $allowedPerPage, true) ? $parsed : 20;
            $perPage = (string) $perPageCount;
        }

        $qrCodes = $query->paginate($perPageCount)->withQueryString();
        $clients = Client::orderBy('name')->get();

        $totalQrs = DynamicQrCode::count();
        $totalScans = (int) DynamicQrCode::sum('scans_count');
        $activeQrs = DynamicQrCode::where('status', 'active')->count();
        $inactiveQrs = DynamicQrCode::where('status', '!=', 'active')->count();
        $assignedQrs = DynamicQrCode::whereNotNull('client_id')->count();

        $metrics = [
            'total' => $totalQrs,
            'scans' => $totalScans,
            'active' => $activeQrs,
            'inactive' => $inactiveQrs,
            'assigned' => $assignedQrs,
            'avg_scans' => $totalQrs > 0 ? round($totalScans / $totalQrs, 1) : 0,
            'active_rate' => $totalQrs > 0 ? round(($activeQrs / $totalQrs) * 100) : 0,
        ];

        return view('admin.dynamic_qrs.index', compact('qrCodes', 'clients', 'metrics', 'perPage'));
    }

    /**
     * Export Dynamic QR Codes list to CSV format
     */
    public function exportCsv(Request $request): Response
    {
        $query = DynamicQrCode::with('client');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('client_id')) {
            if ($request->client_id === 'unassigned') {
                $query->whereNull('client_id');
            } else {
                $query->where('client_id', $request->client_id);
            }
        }

        if ($request->filled('template_type')) {
            $query->where('template_type', $request->template_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $qrs = $query->latest()->get();

        $csv = "Code,Libelle,Client,Destination_URL,Url_Permanente,Gabarit,Scans,Statut,Date_Creation\n";
        foreach ($qrs as $qr) {
            $client = str_replace('"', '""', $qr->client?->name ?? 'Non assigné');
            $label = str_replace('"', '""', $qr->label ?? '');
            $target = str_replace('"', '""', $qr->target_url ?? '');
            $encoded = $qr->encoded_url;
            $csv .= "\"{$qr->code}\",\"{$label}\",\"{$client}\",\"{$target}\",\"{$encoded}\",\"{$qr->template_type}\",{$qr->scans_count},\"{$qr->status}\",\"{$qr->created_at}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="qr_dynamiques_'.date('Ymd_His').'.csv"',
        ]);
    }

    /**
     * Execute bulk operations on selected Dynamic QR Codes (activate, deactivate, assign_client, update_target, delete)
     */
    public function bulkAction(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:dynamic_qr_codes,id',
            'action' => 'required|in:activate,deactivate,assign_client,update_target,delete',
            'client_id' => 'nullable|exists:clients,id',
            'target_url' => 'nullable|string|max:1000',
        ]);

        $ids = $validated['ids'];
        $action = $validated['action'];
        $count = count($ids);

        DB::transaction(function () use ($ids, $action, $validated) {
            $query = DynamicQrCode::whereIn('id', $ids);

            match ($action) {
                'activate' => $query->update(['status' => 'active']),
                'deactivate' => $query->update(['status' => 'inactive']),
                'assign_client' => $query->update(['client_id' => $validated['client_id'] ?? null]),
                'update_target' => ! empty($validated['target_url']) ? $query->update(['target_url' => trim($validated['target_url'])]) : null,
                'delete' => $query->delete(),
            };
        });

        $message = match ($action) {
            'activate' => "{$count} QR code(s) activé(s) avec succès.",
            'deactivate' => "{$count} QR code(s) suspendu(s) avec succès.",
            'assign_client' => "{$count} QR code(s) assigné(s) avec succès.",
            'update_target' => "URL cible mise à jour pour {$count} QR code(s).",
            'delete' => "{$count} QR code(s) supprimé(s).",
        };

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count,
                'action' => $action,
            ]);
        }

        return redirect()->route('admin.dynamic-qrs.index')->with('success', $message);
    }

    /**
     * Show creation view (supports both single & bulk forms)
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $sampleQrSvg = (string) @QrCode::format('svg')->size(300)->margin(0)->generate('https://example.com/demo');
        $designLibrary = $this->getDesignLibraryItems();

        return view('admin.dynamic_qrs.create', compact('clients', 'sampleQrSvg', 'designLibrary'));
    }

    /**
     * Build the list of available Design Library items (System Presets + User Uploaded Designs)
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getDesignLibraryItems(?string $search = null): array
    {
        $items = [];

        // 1. User-uploaded designs from FileUpload table (newest first)
        $uploadsQuery = FileUpload::where('file_type', 'design_template')->latest();
        if ($search !== null && trim($search) !== '') {
            $term = trim($search);
            $uploadsQuery->where(function ($q) use ($term) {
                $q->where('original_name', 'like', "%{$term}%")
                    ->orWhere('mime_type', 'like', "%{$term}%");
            });
        }

        foreach ($uploadsQuery->get() as $upload) {
            $ext = strtoupper(pathinfo($upload->file_path, PATHINFO_EXTENSION) ?: 'IMG');
            $items[] = [
                'id' => $upload->id,
                'name' => $upload->original_name,
                'file_path' => $upload->file_path,
                'url' => $upload->url,
                'size_formatted' => $upload->formatted_size,
                'format' => $ext,
                'is_system' => false,
                'created_at' => $upload->created_at?->format('d/m/Y H:i') ?? '',
                'delete_url' => route('admin.dynamic-qrs.designs.destroy', $upload->id),
            ];
        }

        // 2. Built-in system templates
        $systemTemplates = [
            [
                'id' => 'sys_google_dark',
                'name' => 'Plaque Avis Google (Noir & Or)',
                'file_path' => 'images/templates/google_review_plaque.jpg',
                'format' => 'JPG',
            ],
            [
                'id' => 'sys_google_white',
                'name' => 'Plaque Avis Google (Blanc & Or)',
                'file_path' => 'images/templates/google_review_plaque_white.jpg',
                'format' => 'JPG',
            ],
        ];

        foreach ($systemTemplates as $sys) {
            if (! file_exists(public_path($sys['file_path']))) {
                continue;
            }
            if ($search !== null && trim($search) !== '') {
                $term = mb_strtolower(trim($search));
                if (! str_contains(mb_strtolower($sys['name']), $term) && ! str_contains(mb_strtolower($sys['format']), $term)) {
                    continue;
                }
            }
            $sizeBytes = @filesize(public_path($sys['file_path'])) ?: 0;
            $items[] = [
                'id' => $sys['id'],
                'name' => $sys['name'],
                'file_path' => $sys['file_path'],
                'url' => asset($sys['file_path']),
                'size_formatted' => $sizeBytes > 1024 ? number_format($sizeBytes / 1024, 0).' KB' : $sizeBytes.' B',
                'format' => $sys['format'],
                'is_system' => true,
                'created_at' => 'Système',
                'delete_url' => null,
            ];
        }

        return $items;
    }

    /**
     * List & search designs in the Design Library (JSON API)
     */
    public function listDesigns(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $designs = $this->getDesignLibraryItems($search);

        return response()->json([
            'success' => true,
            'count' => count($designs),
            'designs' => $designs,
        ]);
    }

    /**
     * Upload and add one or more designs to the Design Library
     */
    public function storeDesign(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:150',
            'design_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg|extensions:jpeg,png,jpg,webp,svg|max:12288',
            'design_files' => 'nullable|array',
            'design_files.*' => 'file|mimes:jpeg,png,jpg,webp,svg|extensions:jpeg,png,jpg,webp,svg|max:12288',
        ]);

        $filesToProcess = [];
        if ($request->hasFile('design_file')) {
            $filesToProcess[] = $request->file('design_file');
        }
        if ($request->hasFile('design_files')) {
            foreach ($request->file('design_files') as $f) {
                $filesToProcess[] = $f;
            }
        }

        if (empty($filesToProcess)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez sélectionner au moins un fichier image valide (PNG, JPG, WebP ou SVG).',
                ], 422);
            }

            return back()->withErrors(['design_file' => 'Veuillez sélectionner une image à ajouter.']);
        }

        $createdItems = [];
        $customName = trim((string) $request->input('name', ''));

        foreach ($filesToProcess as $idx => $file) {
            $storedPath = $file->store('templates', 'public');
            $rawOriginal = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanOriginal = ucfirst(str_replace(['_', '-'], ' ', $rawOriginal)) ?: 'Design Personnalisé';

            $displayName = $customName !== ''
                ? (count($filesToProcess) > 1 ? "{$customName} #".($idx + 1) : $customName)
                : $cleanOriginal;

            $upload = FileUpload::create([
                'original_name' => $displayName,
                'file_path' => $storedPath,
                'file_type' => 'design_template',
                'file_size' => $file->getSize() ?: 0,
                'mime_type' => $file->getMimeType() ?: 'image/jpeg',
                'uploaded_by_type' => 'User',
                'uploaded_by_id' => auth('web')->id(),
            ]);

            $ext = strtoupper(pathinfo($upload->file_path, PATHINFO_EXTENSION) ?: 'IMG');
            $createdItems[] = [
                'id' => $upload->id,
                'name' => $upload->original_name,
                'file_path' => $upload->file_path,
                'url' => $upload->url,
                'size_formatted' => $upload->formatted_size,
                'format' => $ext,
                'is_system' => false,
                'created_at' => $upload->created_at?->format('d/m/Y H:i') ?? '',
                'delete_url' => route('admin.dynamic-qrs.designs.destroy', $upload->id),
            ];
        }

        $message = count($createdItems) > 1
            ? count($createdItems).' designs ajoutés à la bibliothèque.'
            : "Design « {$createdItems[0]['name']} » ajouté à la bibliothèque.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'design' => $createdItems[0],
                'designs' => $createdItems,
                'library' => $this->getDesignLibraryItems(),
            ], 201);
        }

        return back()->with('success', $message);
    }

    /**
     * Delete a user-uploaded design from the Design Library
     */
    public function destroyDesign(Request $request, FileUpload $design): JsonResponse|RedirectResponse
    {
        if ($design->file_type !== 'design_template') {
            abort(404);
        }

        $id = $design->id;
        $name = $design->original_name;
        $filePath = $design->file_path;

        $design->delete();

        // Remove file from public disk if no existing DynamicQrCode still relies on it
        $stillUsed = DynamicQrCode::where('template_image', $filePath)->exists();
        if (! $stillUsed && $filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $message = "Design « {$name} » supprimé de la bibliothèque.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => $id,
                'file_path' => $filePath,
                'message' => $message,
                'library' => $this->getDesignLibraryItems(),
            ]);
        }

        return back()->with('success', $message);
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
            'template_type' => 'required|in:google_review,social_media,menu,custom',
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
            'code_font_size' => 'nullable|numeric|min:4|max:120',
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
                $uploadedFile = $request->file('template_image');
                $templateStoragePath = $uploadedFile->store('templates', 'public');
                $absoluteTemplatePath = Storage::disk('public')->path($templateStoragePath);

                if ($request->boolean('save_to_library', true)) {
                    $rawOriginal = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $cleanOriginal = ucfirst(str_replace(['_', '-'], ' ', $rawOriginal)) ?: ($validated['label_prefix'] ?? 'Design Personnalisé');
                    FileUpload::create([
                        'original_name' => $cleanOriginal,
                        'file_path' => $templateStoragePath,
                        'file_type' => 'design_template',
                        'file_size' => $uploadedFile->getSize() ?: 0,
                        'mime_type' => $uploadedFile->getMimeType() ?: 'image/jpeg',
                        'uploaded_by_type' => 'User',
                        'uploaded_by_id' => auth('web')->id(),
                    ]);
                }
            } elseif (! empty($validated['existing_template_image'])) {
                $rawPath = trim($validated['existing_template_image']);
                if (file_exists(public_path($rawPath))) {
                    $absoluteTemplatePath = public_path($rawPath);
                    $templateStoragePath = $rawPath;
                } elseif (Storage::disk('public')->exists($rawPath)) {
                    $absoluteTemplatePath = Storage::disk('public')->path($rawPath);
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
            'template_type' => 'required|in:google_review,social_media,menu,custom',
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
