<?php

namespace App\Services;

use App\Models\Client;
use App\Models\QrCode;
use App\Models\QrScan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Parse incoming request, determine user agent info, and persist scan record
     */
    public function recordScan(QrCode $qr, Request $request): QrScan
    {
        $userAgent = (string) $request->header('User-Agent', '');
        $ip = $request->ip() ?: '127.0.0.1';
        $ipHash = hash('sha256', $ip.config('app.key'));

        $deviceType = $this->detectDeviceType($userAgent);
        $os = $this->detectOperatingSystem($userAgent);
        $browser = $this->detectBrowser($userAgent);

        // Detect Geo if header or cloud provider provides it, otherwise default or simulate
        $country = $request->header('CF-IPCountry')
            ?: $request->header('X-Country')
            ?: ($request->server('GEOIP_COUNTRY_NAME') ?: $this->fallbackCountryByLocale($request));
        $city = $request->header('X-City') ?: ($request->server('GEOIP_CITY') ?: 'Metropolitan Area');

        $scan = QrScan::create([
            'qr_code_id' => $qr->id,
            'scanned_at' => now(),
            'ip_hash' => $ipHash,
            'country' => $country,
            'city' => $city,
            'device_type' => $deviceType,
            'operating_system' => $os,
            'browser' => $browser,
            'referrer' => $request->header('referer'),
            'user_agent' => substr($userAgent, 0, 500),
        ]);

        $qr->increment('scan_count');
        $qr->update(['last_scanned_at' => now()]);

        return $scan;
    }

    /**
     * Compute comprehensive statistics for admin dashboard or specific QR/client
     */
    public function getAnalyticsSummary(?int $qrCodeId = null, ?int $clientId = null, int $days = 30): array
    {
        $query = QrScan::query();

        if ($qrCodeId) {
            $query->where('qr_code_id', $qrCodeId);
        } elseif ($clientId) {
            $query->whereHas('qrCode', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        $now = Carbon::now();
        $todayStart = $now->copy()->startOfDay();
        $yesterdayStart = $now->copy()->subDay()->startOfDay();
        $yesterdayEnd = $now->copy()->subDay()->endOfDay();
        $weekStart = $now->copy()->startOfWeek();
        $monthStart = $now->copy()->startOfMonth();
        $yearStart = $now->copy()->startOfYear();

        $totalScans = (clone $query)->count();
        $uniqueScans = (clone $query)->distinct('ip_hash')->count('ip_hash');
        $scansToday = (clone $query)->where('scanned_at', '>=', $todayStart)->count();
        $scansYesterday = (clone $query)->whereBetween('scanned_at', [$yesterdayStart, $yesterdayEnd])->count();
        $scansThisWeek = (clone $query)->where('scanned_at', '>=', $weekStart)->count();
        $scansThisMonth = (clone $query)->where('scanned_at', '>=', $monthStart)->count();
        $scansThisYear = (clone $query)->where('scanned_at', '>=', $yearStart)->count();

        // Daily chart data for the past $days
        $startDate = $now->copy()->subDays($days - 1)->startOfDay();
        $dailyRaw = (clone $query)
            ->where('scanned_at', '>=', $startDate)
            ->selectRaw('DATE(scanned_at) as date, COUNT(*) as count, COUNT(DISTINCT ip_hash) as unique_count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->all();

        $dailyLabels = [];
        $dailyValues = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = $now->copy()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = Carbon::parse($d)->format('M d');
            $dailyValues[] = $dailyRaw[$d] ?? 0;
        }

        // Device breakdown
        $devices = (clone $query)
            ->select('device_type', DB::raw('count(*) as count'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->pluck('count', 'device_type')
            ->all();

        // OS breakdown
        $os = (clone $query)
            ->select('operating_system', DB::raw('count(*) as count'))
            ->whereNotNull('operating_system')
            ->groupBy('operating_system')
            ->orderByDesc('count')
            ->limit(6)
            ->pluck('count', 'operating_system')
            ->all();

        // Browser breakdown
        $browsers = (clone $query)
            ->select('browser', DB::raw('count(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(6)
            ->pluck('count', 'browser')
            ->all();

        // Country breakdown
        $countries = (clone $query)
            ->select('country', DB::raw('count(*) as count'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(8)
            ->pluck('count', 'country')
            ->all();

        return [
            'total_scans' => $totalScans,
            'unique_scans' => $uniqueScans,
            'scans_today' => $scansToday,
            'scans_yesterday' => $scansYesterday,
            'scans_this_week' => $scansThisWeek,
            'scans_this_month' => $scansThisMonth,
            'scans_this_year' => $scansThisYear,
            'chart_daily' => [
                'labels' => $dailyLabels,
                'data' => $dailyValues,
            ],
            'devices' => $devices,
            'operating_systems' => $os,
            'browsers' => $browsers,
            'countries' => $countries,
        ];
    }

    public function detectDeviceType(string $ua): string
    {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
            return 'tablet';
        }

        if (preg_match('/(mobile|iphone|ipod|blackberry|android|opera mini|windows phone)/i', $ua)) {
            return 'mobile';
        }

        if (preg_match('/(bot|crawl|slurp|spider|mediapartners)/i', $ua)) {
            return 'bot';
        }

        return 'desktop';
    }

    public function detectOperatingSystem(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS X') => 'macOS',
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Other OS',
        };
    }

    public function detectBrowser(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Edg/') => 'Microsoft Edge',
            str_contains($ua, 'Chrome/') && ! str_contains($ua, 'Edg/') => 'Chrome',
            str_contains($ua, 'Safari/') && ! str_contains($ua, 'Chrome/') => 'Safari',
            str_contains($ua, 'Firefox/') => 'Firefox',
            str_contains($ua, 'OPR/') || str_contains($ua, 'Opera/') => 'Opera',
            default => 'Other Browser',
        };
    }

    protected function fallbackCountryByLocale(Request $request): string
    {
        $acceptLang = $request->header('Accept-Language', '');
        if (str_contains($acceptLang, 'en-US')) {
            return 'United States';
        }
        if (str_contains($acceptLang, 'en-GB')) {
            return 'United Kingdom';
        }
        if (str_contains($acceptLang, 'fr')) {
            return 'France';
        }
        if (str_contains($acceptLang, 'de')) {
            return 'Germany';
        }
        if (str_contains($acceptLang, 'ja')) {
            return 'Japan';
        }
        if (str_contains($acceptLang, 'es')) {
            return 'Spain';
        }

        return 'Global';
    }
}
