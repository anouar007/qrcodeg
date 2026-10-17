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
        $client->load(['qrCodes.design', 'qrCodes.content']);

        $qrCodes = $client->qrCodes;
        $totalQrs = $qrCodes->count();
        $activeQrs = $qrCodes->where('status', 'active')->count();

        $qrIds = $qrCodes->pluck('id')->toArray();

        $totalScans = QrScan::whereIn('qr_code_id', $qrIds)->count();
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
            'totalScans',
            'scansToday',
            'scansThisMonth',
            'lastScan',
            'summary',
            'topQrsWithSvg'
        ));
    }
}
