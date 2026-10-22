<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\QrCode;
use App\Models\QrDesign;
use App\Models\QrTemplate;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function __construct(protected QrCodeService $qrCodeService) {}

    public function index(Request $request)
    {
        $query = QrCode::with(['client', 'design', 'content']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('public_code', 'like', "%{$search}%")
                    ->orWhere('access_code', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('company_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'scans_high' => $query->orderByDesc('scan_count'),
            'scans_low' => $query->orderBy('scan_count'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $qrCodes = $query->paginate(20)->withQueryString();
        $clients = Client::orderBy('company_name')->get();
        $templates = QrTemplate::where('is_active', true)->get();

        return view('admin.qr_codes.index', compact('qrCodes', 'clients', 'templates'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();
        $templates = QrTemplate::where('is_active', true)->get();

        return view('admin.qr_codes.create', compact('clients', 'templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:1000',
            'name_prefix' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'client_id' => 'nullable|exists:clients,id',
            'type' => 'required|string|in:website,text,contact,whatsapp,social,image,gallery,video,pdf,product,business,wifi,custom_landing',
            'card_template' => 'nullable|string|in:google_review,social_media,menu',
            'target_redirect_url' => 'nullable|string|max:1000',
            'foreground_color' => 'nullable|string|max:16',
            'background_color' => 'nullable|string|max:16',
            'dot_style' => 'nullable|string|in:square,dots,rounded',
            'corner_style' => 'nullable|string|in:square,dot,rounded,extra-rounded',
            'frame_style' => 'nullable|string|in:none,bottom-bar,badge,card',
            'frame_text' => 'nullable|string|max:50',
            'frame_color' => 'nullable|string|max:16',
            'error_correction' => 'nullable|in:L,M,Q,H',
            'qr_size' => 'nullable|integer|min:100|max:1200',
            // Type specific inputs
            'url' => 'nullable|string|max:500',
            'text' => 'nullable|string|max:2000',
            'contact_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'wifi_ssid' => 'nullable|string|max:100',
            'wifi_password' => 'nullable|string|max:100',
        ]);

        $createdQrs = $this->qrCodeService->batchGenerate($validated, auth('web')->user());
        $count = count($createdQrs);

        ActivityLog::log(
            action: 'qr_created',
            resourceType: 'QrCode',
            resourceId: $createdQrs[0]->id,
            details: ['quantity' => $count, 'prefix' => $validated['name_prefix'], 'type' => $validated['type']]
        );

        $msg = $count === 1
            ? "QR Code '{$createdQrs[0]->name}' generated successfully ({$createdQrs[0]->public_code})."
            : "Successfully batch generated {$count} unique QR codes.";

        return redirect()->route('admin.qr-codes.index')->with('success', $msg);
    }

    public function show(QrCode $qrCode)
    {
        $qrCode->load(['client', 'content', 'design', 'scans' => fn ($q) => $q->latest()->take(25)]);
        $svgPreview = $this->qrCodeService->renderSvg($qrCode, 320);

        return view('admin.qr_codes.show', compact('qrCode', 'svgPreview'));
    }

    public function edit(QrCode $qrCode)
    {
        $qrCode->load(['client', 'content', 'design']);
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();
        $svgPreview = $this->qrCodeService->renderSvg($qrCode, 260);

        return view('admin.qr_codes.edit', compact('qrCode', 'clients', 'svgPreview'));
    }

    public function update(Request $request, QrCode $qrCode)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'client_id' => 'nullable|exists:clients,id',
            'type' => 'required|string|in:website,text,contact,whatsapp,social,image,gallery,video,pdf,product,business,wifi,custom_landing',
            'card_template' => 'nullable|string|in:google_review,social_media,menu',
            'target_redirect_url' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,suspended,archived',
            // Design fields
            'foreground_color' => 'required|string|max:16',
            'background_color' => 'required|string|max:16',
            'dot_style' => 'required|string|in:square,dots,rounded',
            'corner_style' => 'required|string|in:square,dot,rounded,extra-rounded',
            'eye_style' => 'nullable|string|in:square,circle',
            'frame_style' => 'required|string|in:none,bottom-bar,badge,card',
            'frame_text' => 'nullable|string|max:50',
            'frame_color' => 'nullable|string|max:16',
            'error_correction' => 'required|in:L,M,Q,H',
            'qr_size' => 'required|integer|min:150|max:1200',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            // Content JSON data
            'content_data' => 'nullable|array',
        ]);

        $qrCode->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'client_id' => $validated['client_id'] ?? null,
            'type' => $validated['type'],
            'card_template' => $validated['card_template'] ?? ($qrCode->card_template ?: 'google_review'),
            'target_redirect_url' => $validated['target_redirect_url'] ?? null,
            'status' => $validated['status'],
        ]);

        // Update Content
        $contentData = $request->input('content_data', []);
        $qrCode->content()->updateOrCreate(
            ['qr_code_id' => $qrCode->id],
            [
                'type' => $validated['type'],
                'title' => $validated['name'],
                'content_data' => $contentData,
            ]
        );

        // Update Design
        $designData = [
            'foreground_color' => $validated['foreground_color'],
            'background_color' => $validated['background_color'],
            'dot_style' => $validated['dot_style'],
            'corner_style' => $validated['corner_style'],
            'eye_style' => $validated['eye_style'] ?? 'square',
            'frame_style' => $validated['frame_style'],
            'frame_text' => $validated['frame_text'] ?? null,
            'frame_color' => $validated['frame_color'] ?? $validated['foreground_color'],
            'error_correction' => $validated['error_correction'],
            'qr_size' => $validated['qr_size'],
        ];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('qr/logos', 'public');
            $designData['logo_path'] = $logoPath;
        } elseif ($request->boolean('remove_logo')) {
            $designData['logo_path'] = null;
        }

        $qrCode->design()->updateOrCreate(
            ['qr_code_id' => $qrCode->id],
            $designData
        );

        ActivityLog::log(
            action: 'qr_edited',
            resourceType: 'QrCode',
            resourceId: $qrCode->id,
            details: ['name' => $qrCode->name, 'type' => $qrCode->type, 'status' => $qrCode->status]
        );

        return redirect()->route('admin.qr-codes.show', $qrCode)->with('success', "QR Code {$qrCode->name} updated successfully.");
    }

    public function toggleStatus(Request $request, QrCode $qrCode)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended,archived',
        ]);

        $oldStatus = $qrCode->status;
        $qrCode->update(['status' => $request->status]);

        ActivityLog::log(
            action: 'qr_status_changed',
            resourceType: 'QrCode',
            resourceId: $qrCode->id,
            details: ['old' => $oldStatus, 'new' => $qrCode->status]
        );

        return back()->with('success', "QR code status changed to {$qrCode->status}.");
    }

    /**
     * AJAX endpoint for live preview while tweaking colors & styles
     */
    public function previewLive(Request $request, ?QrCode $qrCode = null)
    {
        $fgHex = $request->input('foreground_color', '#000000');
        $bgHex = $request->input('background_color', '#ffffff');
        $frameStyle = $request->input('frame_style', 'none');
        $frameText = $request->input('frame_text', 'SCAN ME');
        $frameColor = $request->input('frame_color', $fgHex);
        $size = (int) $request->input('qr_size', 280);

        $url = $qrCode ? $qrCode->dynamic_url : url('/q/SAMPLE-PREVIEW');

        $tempQr = new QrCode([
            'public_code' => $qrCode ? $qrCode->public_code : 'SAMPLE',
            'name' => $request->input('name', 'Live Preview'),
            'dynamic_url' => $url,
        ]);
        $tempDesign = new QrDesign([
            'foreground_color' => $fgHex,
            'background_color' => $bgHex,
            'frame_style' => $frameStyle,
            'frame_text' => $frameText,
            'frame_color' => $frameColor,
            'qr_size' => $size,
            'error_correction' => $request->input('error_correction', 'M'),
            'dot_style' => $request->input('dot_style', 'square'),
        ]);
        $tempQr->setRelation('design', $tempDesign);

        $svg = $this->qrCodeService->renderSvg($tempQr, $size);

        return response()->json(['svg' => $svg]);
    }

    /**
     * Single QR Downloads (SVG, PNG, PDF)
     */
    public function download(QrCode $qrCode, string $format)
    {
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $qrCode->name ?: $qrCode->public_code);

        ActivityLog::log(
            action: 'qr_downloaded',
            resourceType: 'QrCode',
            resourceId: $qrCode->id,
            details: ['format' => $format]
        );

        if ($format === 'svg') {
            $svg = $this->qrCodeService->renderSvg($qrCode);

            return response($svg, 200, [
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => "attachment; filename=\"{$safeName}_{$qrCode->public_code}.svg\"",
            ]);
        }

        if ($format === 'png') {
            $png = $this->qrCodeService->renderPng($qrCode, 600);

            return response($png, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => "attachment; filename=\"{$safeName}_{$qrCode->public_code}.png\"",
            ]);
        }

        if ($format === 'pdf') {
            $svg = $this->qrCodeService->renderSvg($qrCode, 400);
            $svgDataUri = 'data:image/svg+xml;base64,'.base64_encode($svg);

            $pdf = Pdf::loadView('admin.qr_codes.single_pdf', [
                'qr' => $qrCode,
                'svgDataUri' => $svgDataUri,
            ])->setPaper('a5', 'portrait');

            return $pdf->download("{$safeName}_{$qrCode->public_code}.pdf");
        }

        return back()->with('error', 'Unsupported download format.');
    }

    /**
     * Bulk ZIP download of selected QR codes
     */
    public function downloadBatchZip(Request $request)
    {
        $ids = $request->input('qr_ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Please select at least one QR code to download.');
        }

        $qrCodes = QrCode::whereIn('id', $ids)->with('design')->get();
        if ($qrCodes->isEmpty()) {
            return back()->with('error', 'No matching QR codes found.');
        }

        $zipPath = $this->qrCodeService->createZipArchive($qrCodes->all(), 'qr_bundle_'.time().'.zip');

        ActivityLog::log(
            action: 'qr_batch_downloaded',
            resourceType: 'QrCode',
            details: ['count' => $qrCodes->count()]
        );

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Download printable PDF card in one of the 3 templates (google_review, social_media, menu)
     */
    public function downloadCardPdf(Request $request, QrCode $qrCode)
    {
        $template = $request->input('template', $qrCode->card_template ?: 'google_review');
        $pdf = $this->qrCodeService->generateCardPdf($qrCode, $template);

        ActivityLog::log(
            action: 'qr_card_pdf_downloaded',
            resourceType: 'QrCode',
            resourceId: $qrCode->id,
            details: ['template' => $template]
        );

        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $qrCode->name ?: $qrCode->public_code);

        return $pdf->download("{$safeName}_{$template}_card.pdf");
    }

    /**
     * Quick in-dashboard update of destination link without touching the physical QR code
     */
    public function updateDestination(Request $request, QrCode $qrCode)
    {
        $validated = $request->validate([
            'target_redirect_url' => 'nullable|url|max:1000',
            'card_template' => 'nullable|string|in:google_review,social_media,menu',
            'status' => 'nullable|string|in:active,inactive,suspended,archived',
        ]);

        $qrCode->update($validated);

        ActivityLog::log(
            action: 'qr_destination_updated',
            resourceType: 'QrCode',
            resourceId: $qrCode->id,
            details: $validated
        );

        return back()->with('success', 'Destination link & tracking settings updated instantly! The printed QR code remains 100% active and unchanged.');
    }

    public function destroy(QrCode $qrCode)
    {
        $name = $qrCode->name;
        $qrCode->delete();

        ActivityLog::log(
            action: 'qr_deleted',
            resourceType: 'QrCode',
            resourceId: $qrCode->id,
            details: ['name' => $name]
        );

        return redirect()->route('admin.qr-codes.index')->with('success', "QR Code {$name} moved to trash.");
    }
}
