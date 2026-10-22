<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NfcTag;
use App\Models\NfcTap;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class PublicNfcController extends Controller
{
    public function __construct(protected AnalyticsService $analyticsService) {}

    public function handle(Request $request, string $uid)
    {
        $tag = NfcTag::where('tag_uid', $uid)
            ->with(['client', 'digitalCard', 'qrCode'])
            ->first();

        if (! $tag) {
            return response()->view('qr.unavailable', [
                'title' => 'Tag NFC Introuvable',
                'message' => 'Ce badge ou tag NFC n\'est pas reconnu ou a été désactivé.',
            ], 404);
        }

        if ($tag->status !== 'active') {
            return response()->view('qr.unavailable', [
                'title' => 'Tag NFC Indisponible',
                'message' => 'Ce tag NFC est actuellement inactif ou verrouillé.',
            ], 200);
        }

        // Record tap analytics
        $agent = $request->userAgent() ?? '';
        $os = $this->analyticsService->detectOperatingSystem($agent);
        $browser = $this->analyticsService->detectBrowser($agent);
        $device = $this->analyticsService->detectDeviceType($agent);
        $ipHash = hash('sha256', $request->ip().config('app.key'));

        NfcTap::create([
            'nfc_tag_id' => $tag->id,
            'tapped_at' => now(),
            'ip_hash' => $ipHash,
            'device_type' => $device,
            'operating_system' => $os,
            'browser' => $browser,
            'referrer' => $request->header('referer'),
            'user_agent' => substr($agent, 0, 500),
        ]);

        $tag->increment('tap_count');
        $tag->update(['last_tapped_at' => now()]);

        // Destination resolution
        $destUrl = $tag->destination_url;
        if (! preg_match('#^https?://#i', $destUrl)) {
            $destUrl = 'https://'.$destUrl;
        }

        return redirect()->away($destUrl);
    }
}
