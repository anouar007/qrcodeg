<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyCard;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PublicLoyaltyPassController extends Controller
{
    /**
     * Display the public digital pass for the loyalty cardholder.
     */
    public function show(string $card_code): View
    {
        $card = LoyaltyCard::where('card_code', $card_code)
            ->with(['client', 'transactions' => fn ($q) => $q->latest()->take(10)])
            ->firstOrFail();

        // High resolution QR code rendered inline SVG
        $qrSvg = (string) QrCode::format('svg')
            ->size(200)
            ->margin(1)
            ->color(87, 54, 233)
            ->generate($card->public_url);

        return view('loyalty.public_pass', compact('card', 'qrSvg'));
    }
}
