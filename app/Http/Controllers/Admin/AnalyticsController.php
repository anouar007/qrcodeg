<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\QrCode;
use App\Models\QrScan;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(protected AnalyticsService $analyticsService) {}

    public function index(Request $request)
    {
        $days = (int) $request->input('days', 30);
        $clientId = $request->input('client_id');
        $qrCodeId = $request->input('qr_code_id');

        $summary = $this->analyticsService->getAnalyticsSummary(
            qrCodeId: $qrCodeId ? (int) $qrCodeId : null,
            clientId: $clientId ? (int) $clientId : null,
            days: $days
        );

        $clients = Client::orderBy('company_name')->get();
        $qrCodes = QrCode::orderBy('name')->take(100)->get();

        // Recent scan log entries
        $query = QrScan::with('qrCode.client');
        if ($qrCodeId) {
            $query->where('qr_code_id', $qrCodeId);
        } elseif ($clientId) {
            $query->whereHas('qrCode', fn ($q) => $q->where('client_id', $clientId));
        }

        $scans = $query->latest('scanned_at')->paginate(20)->withQueryString();

        return view('admin.analytics.index', compact('summary', 'clients', 'qrCodes', 'scans', 'days', 'clientId', 'qrCodeId'));
    }

    public function export(Request $request)
    {
        $clientId = $request->input('client_id');
        $qrCodeId = $request->input('qr_code_id');

        $query = QrScan::with(['qrCode.client'])->latest('scanned_at');

        if ($qrCodeId) {
            $query->where('qr_code_id', $qrCodeId);
        } elseif ($clientId) {
            $query->whereHas('qrCode', fn ($q) => $q->where('client_id', $clientId));
        }

        $scans = $query->take(5000)->get();

        ActivityLog::log(
            action: 'analytics_exported',
            resourceType: 'QrScan',
            details: ['count' => $scans->count()]
        );

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="qr_scans_export_'.date('Ymd_His').'.csv"',
        ];

        $callback = function () use ($scans) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Scan ID', 'QR Public Code', 'QR Name', 'Client', 'Scanned At', 'IP Hash', 'Country', 'City', 'Device', 'OS', 'Browser', 'Referrer']);

            foreach ($scans as $scan) {
                fputcsv($handle, [
                    $scan->id,
                    $scan->qrCode?->public_code,
                    $scan->qrCode?->name,
                    $scan->qrCode?->client?->company_name ?: 'Unassigned',
                    $scan->scanned_at?->toIso8601String(),
                    $scan->ip_hash,
                    $scan->country,
                    $scan->city,
                    $scan->device_type,
                    $scan->operating_system,
                    $scan->browser,
                    $scan->referrer,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
