<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\DynamicQrCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QrLinkEditorController extends Controller
{
    /**
     * Display the minimalist QR scanner & link-only editor.
     */
    public function index(Request $request): View
    {
        $initialQr = null;
        if ($request->filled('code')) {
            $code = $this->extractCode((string) $request->query('code'));
            if ($code !== '') {
                $initialQr = DynamicQrCode::where('code', $code)->first();
            }
        }

        return view('qr_link_editor.index', compact('initialQr'));
    }

    /**
     * Lookup a dynamic QR code by raw code or full redirect URL.
     */
    public function lookup(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:500'],
        ]);

        $code = $this->extractCode((string) $request->input('code'));
        if ($code === '') {
            return response()->json([
                'found' => false,
                'message' => 'Invalid QR code.',
            ], 422);
        }

        $qr = DynamicQrCode::where('code', $code)->first();
        if (! $qr) {
            return response()->json([
                'found' => false,
                'message' => "Dynamic QR code '{$code}' not found.",
            ], 404);
        }

        return response()->json([
            'found' => true,
            'qr' => [
                'id' => $qr->id,
                'code' => $qr->code,
                'label' => $qr->label ?: $qr->code,
                'target_url' => $qr->target_url,
                'status' => $qr->status,
            ],
        ]);
    }

    /**
     * Update ONLY the destination link (target_url) of the dynamic QR code.
     */
    public function update(Request $request, DynamicQrCode $dynamicQr): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'target_url' => ['required', 'url', 'max:2048'],
        ]);

        $oldUrl = $dynamicQr->target_url;

        $dynamicQr->update([
            'target_url' => $validated['target_url'],
        ]);

        ActivityLog::log(
            action: 'dynamic_qr_link_updated',
            resourceType: 'DynamicQrCode',
            resourceId: $dynamicQr->id,
            details: [
                'code' => $dynamicQr->code,
                'old_url' => $oldUrl,
                'new_url' => $dynamicQr->target_url,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Link updated for {$dynamicQr->code}.",
                'target_url' => $dynamicQr->target_url,
            ]);
        }

        return back()->with('success', "Link updated for {$dynamicQr->code}.");
    }

    /**
     * Extract the QR code identifier from either a full URL or raw code string.
     */
    private function extractCode(string $raw): string
    {
        $trimmed = trim($raw);
        if ($trimmed === '') {
            return '';
        }

        if (str_contains($trimmed, '://') || str_contains($trimmed, '/')) {
            $path = (string) parse_url($trimmed, PHP_URL_PATH);
            $lastSegment = basename(trim($path, '/'));

            return strtoupper(trim($lastSegment));
        }

        return strtoupper($trimmed);
    }
}
