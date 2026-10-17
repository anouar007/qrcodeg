<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\QrCode;
use App\Models\QrScan;
use App\Models\User;
use App\Services\AnalyticsService;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct(protected AnalyticsService $analyticsService) {}

    public function index()
    {
        $user = auth('web')->user();

        // High-level KPI metrics
        $totalClients = Client::count();
        $totalQrs = QrCode::count();
        $activeQrs = QrCode::where('status', 'active')->count();
        $inactiveQrs = QrCode::where('status', '!=', 'active')->count();

        $totalScans = QrScan::count();
        $scansToday = QrScan::where('scanned_at', '>=', now()->startOfDay())->count();
        $scansThisMonth = QrScan::where('scanned_at', '>=', now()->startOfMonth())->count();
        $totalStaff = User::count();

        // Scans over time (last 14 days)
        $dailySummary = $this->analyticsService->getAnalyticsSummary(days: 14);

        // QR creation over time (last 6 months)
        $qrsByMonthRaw = QrCode::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();

        $qrCreationLabels = [];
        $qrCreationData = [];
        for ($i = 5; $i >= 0; $i--) {
            $mKey = now()->subMonths($i)->format('Y-m');
            $qrCreationLabels[] = now()->subMonths($i)->format('M Y');
            $qrCreationData[] = $qrsByMonthRaw[$mKey] ?? 0;
        }

        // QR codes by type
        $qrsByType = QrCode::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->all();

        // QR codes by status
        $qrsByStatus = QrCode::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        // Device and Geo Breakdown
        $devices = $dailySummary['devices'];
        $countries = $dailySummary['countries'];

        // Recent Activity Logs
        $recentActivities = ActivityLog::with(['user', 'client'])
            ->latest()
            ->take(10)
            ->get();

        // Recent Scans
        $recentScans = QrScan::with('qrCode')
            ->latest('scanned_at')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalClients',
            'totalQrs',
            'activeQrs',
            'inactiveQrs',
            'totalScans',
            'scansToday',
            'scansThisMonth',
            'totalStaff',
            'dailySummary',
            'qrCreationLabels',
            'qrCreationData',
            'qrsByType',
            'qrsByStatus',
            'devices',
            'countries',
            'recentActivities',
            'recentScans'
        ));
    }
}
