<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\QrCode;
use App\Models\QrTemplate;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientQrController extends Controller
{
    public function __construct(protected QrCodeService $qrCodeService) {}

    public function index(Request $request)
    {
        $client = auth('client')->user();
        $query = QrCode::where('client_id', $client->id)->with(['design', 'content']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('public_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $qrCodes = $query->latest()->paginate(15)->withQueryString();

        $qrItemsWithSvg = [];
        foreach ($qrCodes as $qr) {
            $qrItemsWithSvg[] = [
                'qr' => $qr,
                'svg' => $this->qrCodeService->renderSvg($qr, 120),
            ];
        }

        return view('client.qr_codes.index', compact('qrCodes', 'qrItemsWithSvg'));
    }

    public function customize(QrCode $qrCode)
    {
        $this->authorizeClientAccess($qrCode);

        $qrCode->load(['design', 'content']);
        $templates = QrTemplate::where('is_active', true)->get();
        $svgPreview = $this->qrCodeService->renderSvg($qrCode, 280);

        return view('client.qr_codes.customize', compact('qrCode', 'templates', 'svgPreview'));
    }

    public function update(Request $request, QrCode $qrCode)
    {
        $this->authorizeClientAccess($qrCode);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|string|in:website,text,contact,whatsapp,social,image,gallery,video,pdf,product,business,wifi,custom_landing',
            'content_data' => 'nullable|array',
            'foreground_color' => 'required|string|max:16',
            'background_color' => 'required|string|max:16',
            'dot_style' => 'required|string|in:square,dots,rounded',
            'corner_style' => 'required|string|in:square,dot,rounded,extra-rounded',
            'frame_style' => 'required|string|in:none,bottom-bar,badge,card',
            'frame_text' => 'nullable|string|max:50',
            'frame_color' => 'nullable|string|max:16',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $qrCode->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
        ]);

        // Process File Uploads if present (for image, PDF, etc.)
        $contentData = $request->input('content_data', []);

        if ($request->hasFile('content_image')) {
            $path = $request->file('content_image')->store('uploads/images', 'public');
            $contentData['image_url'] = Storage::disk('public')->url($path);
        }

        if ($request->hasFile('content_pdf')) {
            $path = $request->file('content_pdf')->store('uploads/pdfs', 'public');
            $contentData['pdf_url'] = Storage::disk('public')->url($path);
            $contentData['pdf_name'] = $request->file('content_pdf')->getClientOriginalName();
        }

        $qrCode->content()->updateOrCreate(
            ['qr_code_id' => $qrCode->id],
            [
                'type' => $validated['type'],
                'title' => $validated['name'],
                'content_data' => $contentData,
            ]
        );

        $designData = [
            'foreground_color' => $validated['foreground_color'],
            'background_color' => $validated['background_color'],
            'dot_style' => $validated['dot_style'],
            'corner_style' => $validated['corner_style'],
            'frame_style' => $validated['frame_style'],
            'frame_text' => $validated['frame_text'] ?? null,
            'frame_color' => $validated['frame_color'] ?? $validated['foreground_color'],
        ];

        if ($request->hasFile('logo')) {
            $designData['logo_path'] = $request->file('logo')->store('clients/qr_logos', 'public');
        } elseif ($request->boolean('remove_logo')) {
            $designData['logo_path'] = null;
        }

        $qrCode->design()->updateOrCreate(
            ['qr_code_id' => $qrCode->id],
            $designData
        );

        ActivityLog::log(
            action: 'client_qr_customized',
            resourceType: 'QrCode',
            resourceId: $qrCode->id,
            details: ['name' => $qrCode->name, 'type' => $qrCode->type],
            client: auth('client')->user()
        );

        return redirect()->route('client.qr-codes.index')->with('success', "QR code '{$qrCode->name}' customized successfully! Dynamic changes are now live.");
    }

    public function download(QrCode $qrCode, string $format)
    {
        $this->authorizeClientAccess($qrCode);

        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $qrCode->name ?: $qrCode->public_code);

        ActivityLog::log(
            action: 'client_qr_downloaded',
            resourceType: 'QrCode',
            resourceId: $qrCode->id,
            details: ['format' => $format],
            client: auth('client')->user()
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

    protected function authorizeClientAccess(QrCode $qrCode): void
    {
        $client = auth('client')->user();
        if ($qrCode->client_id !== $client->id) {
            abort(403, 'Unauthorized access to this QR code.');
        }
    }
}
