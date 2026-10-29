<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DigitalCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublicDigitalCardController extends Controller
{
    /**
     * Display the public standalone digital business card.
     */
    public function show(Request $request, string $slug)
    {
        $card = DigitalCard::where('slug', $slug)
            ->with('client')
            ->firstOrFail();

        if (! $card->isActive()) {
            return response()->view('qr.unavailable', [
                'title' => 'Digital Card Unavailable',
                'message' => 'This digital business card is currently deactivated or unavailable.',
            ], 200);
        }

        // Basic rate limiting for view counter (1 increment per IP every 15 minutes)
        $cacheKey = 'card_view_'.$card->id.'_'.$request->ip();
        if (! Cache::has($cacheKey)) {
            $card->increment('views_count');
            $card->update(['last_viewed_at' => now()]);
            Cache::put($cacheKey, true, now()->addMinutes(15));
        }

        return view('digital_cards.public', compact('card'));
    }

    /**
     * Generate and download the vCard (.vcf) file.
     */
    public function downloadVCard(string $slug)
    {
        $card = DigitalCard::where('slug', $slug)->firstOrFail();
        $vcard = $card->generateVCard();
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $card->full_name ?: ($card->title ?: $card->name));

        return response($vcard, 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$safeName}.vcf\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
