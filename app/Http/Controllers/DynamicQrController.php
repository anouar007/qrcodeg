<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DynamicQrController extends Controller
{
    public function __construct(protected AnalyticsService $analyticsService) {}

    public function handle(Request $request, string $publicCode)
    {
        $qr = QrCode::where('public_code', $publicCode)
            ->with(['content', 'design', 'client'])
            ->first();

        if (! $qr) {
            return response()->view('qr.unavailable', [
                'title' => 'QR Code Not Found',
                'message' => 'The scanned QR code is invalid or does not exist.',
            ], 404);
        }

        // Verify status
        if ($qr->status !== 'active') {
            return response()->view('qr.unavailable', [
                'title' => 'QR Code Unavailable',
                'message' => 'This QR code is currently unavailable.',
            ], 200);
        }

        // Check if client account itself is inactive
        if ($qr->client && ! $qr->client->isActive()) {
            return response()->view('qr.unavailable', [
                'title' => 'QR Code Unavailable',
                'message' => 'This QR code is currently unavailable.',
            ], 200);
        }

        // Record scan statistics anonymously
        $this->analyticsService->recordScan($qr, $request);

        // If explicit destination redirect URL is configured, redirect immediately
        if (! empty($qr->target_redirect_url)) {
            $destUrl = $qr->target_redirect_url;
            if (! preg_match('#^https?://#i', $destUrl)) {
                $destUrl = 'https://'.$destUrl;
            }

            return redirect()->away($destUrl);
        }

        $content = $qr->content?->content_data ?? [];
        $type = $qr->type;

        // If website and auto-redirect is enabled (or simple website with url)
        if ($type === 'website' && ! empty($content['url']) && ($content['auto_redirect'] ?? true)) {
            $destUrl = $content['url'];
            if (! preg_match('#^https?://#i', $destUrl)) {
                $destUrl = 'https://'.$destUrl;
            }

            return redirect()->away($destUrl);
        }

        // Render dynamic mobile-first landing view
        return view('qr.scan', [
            'qr' => $qr,
            'content' => $content,
            'design' => $qr->design,
            'type' => $type,
        ]);
    }

    /**
     * Download vCard .vcf file for contact QR codes
     */
    public function downloadVCard(string $publicCode)
    {
        $qr = QrCode::where('public_code', $publicCode)->with('content')->firstOrFail();
        $data = $qr->content?->content_data ?? [];

        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= 'FN:'.($data['name'] ?? $qr->name)."\r\n";
        if (! empty($data['company'])) {
            $vcard .= 'ORG:'.$data['company']."\r\n";
        }
        if (! empty($data['job_title'])) {
            $vcard .= 'TITLE:'.$data['job_title']."\r\n";
        }
        if (! empty($data['phone'])) {
            $vcard .= 'TEL;TYPE=CELL:'.$data['phone']."\r\n";
        }
        if (! empty($data['email'])) {
            $vcard .= 'EMAIL:'.$data['email']."\r\n";
        }
        if (! empty($data['website'])) {
            $vcard .= 'URL:'.$data['website']."\r\n";
        }
        if (! empty($data['address'])) {
            $vcard .= 'ADR;TYPE=WORK:;;'.$data['address'].";;;;\r\n";
        }
        $vcard .= "END:VCARD\r\n";

        $filename = 'contact_'.preg_replace('/[^a-zA-Z0-9]/', '_', $data['name'] ?? $qr->public_code).'.vcf';

        return response($vcard, 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
