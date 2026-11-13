<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DynamicQrCode;
use App\Services\DynamicQrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ClientDynamicQrController extends Controller
{
    public function __construct(
        protected DynamicQrCodeService $qrService
    ) {}

    /**
     * Display a listing of dynamic QR codes owned by the authenticated client.
     */
    public function index(Request $request): View
    {
        $client = auth('client')->user();

        // Strict ownership scoping: only the authenticated client's records
        $query = DynamicQrCode::where('client_id', $client->id)->with('scanLogs');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('label', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dynamicQrs = $query->latest()->paginate(15)->withQueryString();

        return view('client.dynamic_qrs.index', compact('client', 'dynamicQrs'));
    }

    /**
     * Show the edit / configuration form for a client's dynamic QR code.
     */
    public function edit(DynamicQrCode $dynamicQr): View
    {
        $this->authorizeClientAccess($dynamicQr);

        $svgPreview = $this->qrService->renderSvg($dynamicQr, 200);

        return view('client.dynamic_qrs.edit', compact('dynamicQr', 'svgPreview'));
    }

    /**
     * Update the destination target URL and metadata for a client's dynamic QR code.
     */
    public function update(Request $request, DynamicQrCode $dynamicQr): RedirectResponse|JsonResponse
    {
        $this->authorizeClientAccess($dynamicQr);

        $validated = $request->validate([
            'label' => 'required|string|max:150',
            'target_url' => 'required|string|max:1000',
            'template_type' => 'nullable|string|in:google_review,social_media,menu',
        ]);

        $oldUrl = $dynamicQr->target_url;

        $dynamicQr->update([
            'label' => $validated['label'],
            'target_url' => $validated['target_url'],
            'template_type' => $validated['template_type'] ?? $dynamicQr->template_type,
        ]);

        ActivityLog::log(
            action: 'client_dynamic_qr_target_updated',
            resourceType: 'DynamicQrCode',
            resourceId: $dynamicQr->id,
            details: [
                'code' => $dynamicQr->code,
                'old_target_url' => $oldUrl,
                'new_target_url' => $dynamicQr->target_url,
            ],
            client: auth('client')->user()
        );

        $message = "La destination du QR Code {$dynamicQr->code} a été mise à jour avec succès. Vos supports physiques restent 100% actifs sans réimpression !";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'target_url' => $dynamicQr->target_url,
            ]);
        }

        return redirect()->route('client.dynamic-qrs.index')->with('success', $message);
    }

    /**
     * Download the QR code in SVG, PNG, or printable card PDF.
     */
    public function download(Request $request, DynamicQrCode $dynamicQr, string $format): Response
    {
        $this->authorizeClientAccess($dynamicQr);

        ActivityLog::log(
            action: 'client_dynamic_qr_downloaded',
            resourceType: 'DynamicQrCode',
            resourceId: $dynamicQr->id,
            details: ['format' => $format],
            client: auth('client')->user()
        );

        if ($format === 'svg') {
            $svg = $this->qrService->renderSvg($dynamicQr, 600);

            return response($svg, 200, [
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => "attachment; filename=\"{$dynamicQr->code}_vector.svg\"",
            ]);
        }

        if ($format === 'png') {
            $png = $this->qrService->renderPng($dynamicQr, 600);

            return response($png, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => "attachment; filename=\"{$dynamicQr->code}_hires.png\"",
            ]);
        }

        if ($format === 'pdf') {
            $template = $request->input('template', $dynamicQr->template_type ?: 'google_review');
            $pdf = $this->qrService->generateCardPdf($dynamicQr, $template, '85x55');

            return $pdf->download("{$dynamicQr->code}_card.pdf");
        }

        abort(400, 'Format non pris en charge.');
    }

    /**
     * Verify that the requested dynamic QR code belongs strictly to the authenticated client.
     */
    protected function authorizeClientAccess(DynamicQrCode $dynamicQr): void
    {
        $client = auth('client')->user();
        if ($dynamicQr->client_id !== $client->id) {
            abort(403, 'Accès non autorisé à ce QR Code dynamique.');
        }
    }
}
