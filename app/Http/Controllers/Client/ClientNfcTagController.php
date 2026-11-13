<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientNfcTagController extends Controller
{
    /**
     * Display a listing of NFC tags and touch cards owned by the authenticated client.
     */
    public function index(Request $request): View
    {
        $client = auth('client')->user();

        // Strict ownership scoping: only the authenticated client's records
        $query = NfcTag::where('client_id', $client->id)->with('digitalCard');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('tag_uid', 'like', "%{$search}%")
                    ->orWhere('destination_url', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $nfcTags = $query->latest()->paginate(15)->withQueryString();

        return view('client.nfc_tags.index', compact('client', 'nfcTags'));
    }

    /**
     * Show the destination swap and configuration form for a client's NFC tag.
     */
    public function edit(NfcTag $nfcTag): View
    {
        $this->authorizeClientAccess($nfcTag);

        $client = auth('client')->user();

        // Strictly client's own digital cards and dynamic QRs for presets
        $clientCards = DigitalCard::where('client_id', $client->id)->where('status', 'active')->get();
        $clientDynamicQrs = DynamicQrCode::where('client_id', $client->id)->where('status', 'active')->get();

        return view('client.nfc_tags.edit', compact('nfcTag', 'clientCards', 'clientDynamicQrs'));
    }

    /**
     * Update the destination URL or target routing for the client's NFC tag.
     */
    public function update(Request $request, NfcTag $nfcTag): RedirectResponse
    {
        $this->authorizeClientAccess($nfcTag);
        $client = auth('client')->user();

        $validated = $request->validate([
            'label' => 'required|string|max:150',
            'target_type' => 'required|string|in:url,digital_card,qr_code',
            'destination_url' => 'nullable|string|max:1000',
            'digital_card_id' => 'nullable|integer',
            'dynamic_qr_id' => 'nullable|integer',
        ]);

        $destUrl = $validated['destination_url'] ?? null;
        $digitalCardId = null;

        if ($validated['target_type'] === 'digital_card' && ! empty($validated['digital_card_id'])) {
            // Ensure the selected digital card belongs to the client
            $card = DigitalCard::where('id', $validated['digital_card_id'])
                ->where('client_id', $client->id)
                ->firstOrFail();

            $destUrl = route('digital-cards.public.show', $card->slug);
            $digitalCardId = $card->id;
        } elseif ($validated['target_type'] === 'qr_code' && ! empty($validated['dynamic_qr_id'])) {
            // Ensure the selected dynamic QR belongs to the client
            $dqr = DynamicQrCode::where('id', $validated['dynamic_qr_id'])
                ->where('client_id', $client->id)
                ->firstOrFail();

            $destUrl = route('qr.redirect', ['code' => $dqr->code]);
        }

        if (empty($destUrl)) {
            return back()->withErrors(['destination_url' => 'Veuillez spécifier une URL de destination valide.'])->withInput();
        }

        $oldUrl = $nfcTag->destination_url ?: $nfcTag->target_url;

        $nfcTag->update([
            'label' => $validated['label'],
            'name' => $validated['label'],
            'target_type' => $validated['target_type'],
            'destination_url' => $destUrl,
            'target_url' => $destUrl,
            'digital_card_id' => $digitalCardId,
        ]);

        ActivityLog::log(
            action: 'client_nfc_destination_swapped',
            resourceType: 'NfcTag',
            resourceId: $nfcTag->id,
            details: [
                'tag_uid' => $nfcTag->tag_uid,
                'old_destination' => $oldUrl,
                'new_destination' => $destUrl,
            ],
            client: $client
        );

        return redirect()->route('client.nfc-tags.index')
            ->with('success', "Puce NFC '{$nfcTag->label}' mise à jour avec succès vers : {$destUrl}");
    }

    /**
     * Bind a captured NFC chip UID to this NFC Tag after browser NFC write
     */
    public function bindUid(Request $request, NfcTag $nfcTag): JsonResponse
    {
        $this->authorizeClientAccess($nfcTag);

        $validated = $request->validate([
            'tag_uid' => 'required|string|max:100',
        ]);

        $nfcTag->update([
            'tag_uid' => trim($validated['tag_uid']),
        ]);

        return response()->json([
            'success' => true,
            'message' => "UID {$nfcTag->tag_uid} associé avec succès à la puce '{$nfcTag->label}' !",
            'tag_uid' => $nfcTag->tag_uid,
        ]);
    }

    /**
     * Verify that the requested NFC tag belongs strictly to the authenticated client.
     */
    protected function authorizeClientAccess(NfcTag $nfcTag): void
    {
        $client = auth('client')->user();
        if ($nfcTag->client_id !== $client->id) {
            abort(403, 'Accès non autorisé à cette puce NFC.');
        }
    }
}
