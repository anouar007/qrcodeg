<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\DigitalCard;
use App\Models\NfcTag;
use App\Models\NfcTap;
use App\Models\QrCode;
use App\Models\QrScan;
use App\Models\Role;
use App\Models\User;
use App\Services\AnalyticsService;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct(protected AnalyticsService $analyticsService) {}

    public function index()
    {
        // ------------------------------------------------------------------
        // 1. OVERALL STATS & SUMMARY
        // ------------------------------------------------------------------
        $qrScansToday = QrScan::where('scanned_at', '>=', now()->startOfDay())->count();
        $nfcTapsToday = NfcTap::where('tapped_at', '>=', now()->startOfDay())->count();
        $totalInteractionsToday = $qrScansToday + $nfcTapsToday;

        $totalQrScans = QrScan::count();
        $totalNfcTaps = NfcTap::count();
        $totalCardViews = DigitalCard::sum('views_count');
        $totalOverallInteractions = $totalQrScans + $totalNfcTaps + $totalCardViews;

        // ------------------------------------------------------------------
        // 2. CLIENTS SECTION STATS
        // ------------------------------------------------------------------
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'active')->count();
        $inactiveClients = Client::where('status', 'inactive')->count();
        $distinctCitiesCount = Client::whereNotNull('city')->where('city', '!=', '')->distinct('city')->count('city');
        $recentClients = Client::withCount(['qrCodes', 'digitalCards', 'nfcTags'])->latest()->take(5)->get();

        // ------------------------------------------------------------------
        // 3. CARTE VISITE DIGITALE STATS
        // ------------------------------------------------------------------
        $totalDigitalCards = DigitalCard::count();
        $activeDigitalCards = DigitalCard::where('status', 'active')->count();
        $cardsByTemplate = DigitalCard::select('template', DB::raw('count(*) as count'))
            ->groupBy('template')
            ->pluck('count', 'template')
            ->all();
        $topViewedCards = DigitalCard::with('client')
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        // ------------------------------------------------------------------
        // 4. NFC TAGS STATS
        // ------------------------------------------------------------------
        $totalNfcTags = NfcTag::count();
        $activeNfcTags = NfcTag::where('status', 'active')->count();
        $nfcByTargetType = NfcTag::select('target_type', DB::raw('count(*) as count'))
            ->groupBy('target_type')
            ->pluck('count', 'target_type')
            ->all();
        $recentNfcTaps = NfcTap::with('nfcTag.client')
            ->latest('tapped_at')
            ->take(5)
            ->get();

        // ------------------------------------------------------------------
        // 5. DYNAMIC QR CODES (VP) STATS
        // ------------------------------------------------------------------
        $totalQrs = QrCode::count();
        $activeQrs = QrCode::where('status', 'active')->count();
        $inactiveQrs = QrCode::where('status', '!=', 'active')->count();
        $scansThisMonth = QrScan::where('scanned_at', '>=', now()->startOfMonth())->count();
        $qrsByCardTemplate = QrCode::select('card_template', DB::raw('count(*) as count'))
            ->groupBy('card_template')
            ->pluck('count', 'card_template')
            ->all();
        $topScannedQrs = QrCode::with('client')
            ->orderByDesc('scan_count')
            ->take(5)
            ->get();

        // ------------------------------------------------------------------
        // 6. USERS & STAFF STATS
        // ------------------------------------------------------------------
        $totalStaff = User::count();
        $activeStaff = User::where('status', 'active')->count();
        $totalRoles = Role::count();
        $recentUsers = User::with('roles')->latest()->take(5)->get();

        // ------------------------------------------------------------------
        // 7. CHART.JS DATASETS
        // ------------------------------------------------------------------
        // 14-day history for QR scans and NFC taps
        $chartDates = [];
        $qrScansChartData = [];
        $nfcTapsChartData = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateLabel = now()->subDays($i)->format('d M');
            $chartDates[] = $dateLabel;

            $qrScansChartData[] = QrScan::whereDate('scanned_at', $date)->count();
            $nfcTapsChartData[] = NfcTap::whereDate('tapped_at', $date)->count();
        }

        // Recent Activity Logs (Audit Trail)
        $recentActivities = ActivityLog::with(['user', 'client'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            // Overall
            'totalInteractionsToday',
            'qrScansToday',
            'nfcTapsToday',
            'totalOverallInteractions',
            'totalQrScans',
            'totalNfcTaps',
            'totalCardViews',
            // Clients
            'totalClients',
            'activeClients',
            'inactiveClients',
            'distinctCitiesCount',
            'recentClients',
            // Digital Cards
            'totalDigitalCards',
            'activeDigitalCards',
            'cardsByTemplate',
            'topViewedCards',
            // NFC
            'totalNfcTags',
            'activeNfcTags',
            'nfcByTargetType',
            'recentNfcTaps',
            // QR Codes
            'totalQrs',
            'activeQrs',
            'inactiveQrs',
            'scansThisMonth',
            'qrsByCardTemplate',
            'topScannedQrs',
            // Users
            'totalStaff',
            'activeStaff',
            'totalRoles',
            'recentUsers',
            // Charts & Feed
            'chartDates',
            'qrScansChartData',
            'nfcTapsChartData',
            'recentActivities'
        ));
    }
}
