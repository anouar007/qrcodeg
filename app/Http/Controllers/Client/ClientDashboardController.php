<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\QrScan;
use App\Services\AnalyticsService;
use App\Services\QrCodeService;

class ClientDashboardController extends Controller
{
    public function __construct(
        protected AnalyticsService $analyticsService,
        protected QrCodeService $qrCodeService
    ) {}

    public function index()
    {
        $client = auth('client')->user();
        $client->load([
            'qrCodes.design',
            'qrCodes.content',
            'digitalCards',
            'dynamicQrCodes',
            'nfcTags',
            'loyaltyCards',
        ]);

        $qrCodes = $client->qrCodes;
        $totalQrs = $qrCodes->count();
        $activeQrs = $qrCodes->where('status', 'active')->count();

        $digitalCards = $client->digitalCards;
        $totalDigitalCards = $digitalCards->count();
        $totalCardViews = (int) $digitalCards->sum('views_count');

        $dynamicQrs = $client->dynamicQrCodes;
        $totalDynamicQrs = $dynamicQrs->count();
        $dynamicQrScans = (int) $dynamicQrs->sum('scans_count');

        $nfcTags = $client->nfcTags;
        $totalNfcTags = $nfcTags->count();
        $nfcTaps = (int) ($nfcTags->sum('scans_count') + $nfcTags->sum('tap_count'));

        $loyaltyCards = $client->loyaltyCards;
        $totalLoyaltyCards = $loyaltyCards->count();
        $loyaltyLimit = (int) ($client->loyalty_card_limit ?? 20);

        $totalProducts = $totalQrs + $totalDigitalCards + $totalDynamicQrs + $totalNfcTags + $totalLoyaltyCards;

        $qrIds = $qrCodes->pluck('id')->toArray();

        $totalScans = QrScan::whereIn('qr_code_id', $qrIds)->count() + $dynamicQrScans + $nfcTaps;
        $scansToday = QrScan::whereIn('qr_code_id', $qrIds)->where('scanned_at', '>=', now()->startOfDay())->count();
        $scansThisMonth = QrScan::whereIn('qr_code_id', $qrIds)->where('scanned_at', '>=', now()->startOfMonth())->count();

        $lastScan = QrScan::whereIn('qr_code_id', $qrIds)->latest('scanned_at')->first();

        // Chart data
        $summary = $this->analyticsService->getAnalyticsSummary(clientId: $client->id, days: 14);

        // Pre-render SVG thumbnails for top QR codes
        $topQrs = $qrCodes->sortByDesc('scan_count')->take(4);
        $topQrsWithSvg = [];
        foreach ($topQrs as $qr) {
            $topQrsWithSvg[] = [
                'qr' => $qr,
                'svg' => $this->qrCodeService->renderSvg($qr, 140),
            ];
        }

        return view('client.dashboard', compact(
            'client',
            'totalQrs',
            'activeQrs',
            'totalDigitalCards',
            'totalCardViews',
            'totalDynamicQrs',
            'totalNfcTags',
            'totalProducts',
            'totalScans',
            'scansToday',
            'scansThisMonth',
            'lastScan',
            'summary',
            'topQrsWithSvg',
            'digitalCards',
            'dynamicQrs',
            'nfcTags',
            'loyaltyCards',
            'totalLoyaltyCards',
            'loyaltyLimit'
        ));
    }
}
