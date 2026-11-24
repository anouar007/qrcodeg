<?php

namespace App\Http\Controllers;

use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use App\Models\RestaurantMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuCardScannerController extends Controller
{
    /**
     * Display the minimalist QR & NFC scanner page for menus and digital cards.
     */
    public function index(): View
    {
        return view('menu_card_scanner.index');
    }

    /**
     * Resolve a scanned QR code or NFC payload to a Menu or Digital Card edit URL.
     */
    public function lookup(Request $request): JsonResponse
    {
        $request->validate([
            'payload' => ['required', 'string', 'max:1000'],
        ]);

        $raw = trim((string) $request->input('payload'));
        $resolved = $this->resolveTarget($raw);

        if (! $resolved) {
            return response()->json([
                'found' => false,
                'message' => 'No menu or digital card found for this QR code or NFC tag.',
            ], 404);
        }

        if ($resolved instanceof RestaurantMenu) {
            $ids = session('scanned_menu_ids', []);
            if (! in_array($resolved->id, $ids, true)) {
                $ids[] = $resolved->id;
                session(['scanned_menu_ids' => $ids]);
            }

            return response()->json([
                'found' => true,
                'type' => 'menu',
                'title' => $resolved->title,
                'edit_url' => route('admin.menus.edit', $resolved),
            ]);
        }

        $ids = session('scanned_card_ids', []);
        if (! in_array($resolved->id, $ids, true)) {
            $ids[] = $resolved->id;
            session(['scanned_card_ids' => $ids]);
        }

        return response()->json([
            'found' => true,
            'type' => 'digital_card',
            'title' => $resolved->full_name ?: $resolved->name,
            'edit_url' => route('admin.digital-cards.edit', $resolved),
        ]);
    }

    /**
     * Resolve a raw string/URL into a RestaurantMenu or DigitalCard if possible.
     */
    private function resolveTarget(string $raw, int $depth = 0): RestaurantMenu|DigitalCard|null
    {
        if ($raw === '' || $depth > 2) {
            return null;
        }

        $path = str_contains($raw, '://') || str_contains($raw, '/')
            ? trim((string) parse_url($raw, PHP_URL_PATH), '/')
            : '';

        // 1. Direct Menu URL: /m/{slug}
        if (preg_match('#^m/([^/]+)$#i', $path, $m)) {
            return RestaurantMenu::where('slug', $m[1])->first();
        }

        // 2. Direct Digital Card URL: /c/{slug} or /c/{slug}/menu
        if (preg_match('#^c/([^/]+)(?:/menu)?$#i', $path, $m)) {
            return DigitalCard::where('slug', $m[1])->first();
        }

        // 3. Dynamic QR Code URL (/qr/{code} or /q/{code}) or raw VP-XXXXXX code
        $qrCode = null;
        if (preg_match('#^(?:qr|q)/([^/]+)$#i', $path, $m)) {
            $qrCode = strtoupper($m[1]);
        } elseif ($path === '' && str_starts_with(strtoupper($raw), 'VP-')) {
            $qrCode = strtoupper($raw);
        }

        if ($qrCode) {
            $qr = DynamicQrCode::where('code', $qrCode)->first();
            if ($qr && $qr->target_url) {
                return $this->resolveTarget($qr->target_url, $depth + 1);
            }
        }

        // 4. NFC Tag URL (/nfc/{id_or_uid} or /n/{uid}) or raw NFC UID
        $nfcIdentifier = null;
        if (preg_match('#^(?:nfc|n)/([^/]+)$#i', $path, $m)) {
            $nfcIdentifier = $m[1];
        } elseif ($path === '') {
            $nfcIdentifier = $raw;
        }

        if ($nfcIdentifier) {
            $tag = NfcTag::where('tag_uid', $nfcIdentifier)
                ->orWhere('uid', $nfcIdentifier)
                ->when(is_numeric($nfcIdentifier), fn ($q) => $q->orWhere('id', (int) $nfcIdentifier))
                ->first();

            if ($tag) {
                if ($tag->digitalCard) {
                    return $tag->digitalCard;
                }

                $dest = $tag->destination_url ?: $tag->target_url;
                if ($dest) {
                    return $this->resolveTarget($dest, $depth + 1);
                }
            }
        }

        return null;
    }
}
