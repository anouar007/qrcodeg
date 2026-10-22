<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DigitalCard;
use Illuminate\Http\Request;

class PublicDigitalCardController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $card = DigitalCard::where('slug', $slug)
            ->with('client')
            ->firstOrFail();

        if (! $card->isActive()) {
            return response()->view('qr.unavailable', [
                'title' => 'Carte Visite Indisponible',
                'message' => 'Cette carte de visite digitale est actuellement désactivée ou indisponible.',
            ], 200);
        }

        // Increment views count
        $card->increment('views_count');
        $card->update(['last_viewed_at' => now()]);

        return view('digital_cards.public', compact('card'));
    }

    public function downloadVCard(string $slug)
    {
        $card = DigitalCard::where('slug', $slug)->firstOrFail();
        $vcard = $card->generateVCard();
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $card->full_name ?: $card->name);

        return response($vcard, 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$safeName}.vcf\"",
        ]);
    }
}
