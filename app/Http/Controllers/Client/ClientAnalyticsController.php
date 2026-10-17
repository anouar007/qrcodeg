<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\QrScan;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class ClientAnalyticsController extends Controller
{
    public function __construct(protected AnalyticsService $analyticsService) {}

    public function index(Request $request)
    {
        $client = auth('client')->user();
        $days = (int) $request->input('days', 30);
        $qrCodeId = $request->input('qr_code_id');

        $myQrs = QrCode::where('client_id', $client->id)->orderBy('name')->get();
        $validQrId = null;

        if ($qrCodeId) {
            $matchingQr = $myQrs->firstWhere('id', (int) $qrCodeId);
            if ($matchingQr) {
                $validQrId = $matchingQr->id;
            }
        }

        $summary = $this->analyticsService->getAnalyticsSummary(
            qrCodeId: $validQrId,
            clientId: $client->id,
            days: $days
        );

        $query = QrScan::whereHas('qrCode', fn ($q) => $q->where('client_id', $client->id))->with('qrCode');
        if ($validQrId) {
            $query->where('qr_code_id', $validQrId);
        }

        $scans = $query->latest('scanned_at')->paginate(20)->withQueryString();

        return view('client.analytics.index', compact('summary', 'myQrs', 'scans', 'days', 'validQrId'));
    }
}
