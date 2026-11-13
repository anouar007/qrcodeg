<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientProductController extends Controller
{
    /**
     * Display a consolidated hub of all products purchased and owned by the authenticated client.
     */
    public function index(Request $request): View
    {
        $client = auth('client')->user();

        // Strictly scoped to the authenticated client only
        $digitalCards = $client->digitalCards()->latest()->get();
        $dynamicQrs = $client->dynamicQrCodes()->latest()->get();
        $nfcTags = $client->nfcTags()->with('digitalCard')->latest()->get();
        $qrCodes = $client->qrCodes()->with(['design', 'content'])->latest()->get();
        $loyaltyCards = $client->loyaltyCards()->latest()->get();

        $totalProducts = $digitalCards->count() + $dynamicQrs->count() + $nfcTags->count() + $qrCodes->count() + $loyaltyCards->count();

        $activeProducts = $digitalCards->where('status', 'active')->count()
            + $dynamicQrs->where('status', 'active')->count()
            + $nfcTags->where('status', 'active')->count()
            + $qrCodes->where('status', 'active')->count()
            + $loyaltyCards->where('status', 'active')->count();

        $totalScansAndTaps = (int) (
            $dynamicQrs->sum('scans_count')
            + $qrCodes->sum('scan_count')
            + $nfcTags->sum('scans_count')
            + $nfcTags->sum('tap_count')
        );

        $totalCardViews = (int) $digitalCards->sum('views_count');

        // Optional filter tab
        $activeTab = $request->query('tab', 'all');

        return view('client.products.index', compact(
            'client',
            'digitalCards',
            'dynamicQrs',
            'nfcTags',
            'qrCodes',
            'loyaltyCards',
            'totalProducts',
            'activeProducts',
            'totalScansAndTaps',
            'totalCardViews',
            'activeTab'
        ));
    }
}
