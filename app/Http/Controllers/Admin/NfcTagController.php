<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\DigitalCard;
use App\Models\NfcTag;
use App\Models\QrCode;
use Illuminate\Http\Request;

class NfcTagController extends Controller
{
    public function index(Request $request)
    {
        $query = NfcTag::with(['client', 'digitalCard', 'qrCode']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('tag_uid', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('company_name', 'like', "%{$search}%")->orWhere('contact_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('target_type')) {
            $query->where('target_type', $request->target_type);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $nfcTags = $query->latest()->paginate(15)->withQueryString();
        $clients = Client::orderBy('company_name')->get();

        return view('admin.nfc_tags.index', compact('nfcTags', 'clients'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();
        $digitalCards = DigitalCard::where('status', 'active')->latest()->get();
        $qrCodes = QrCode::where('status', 'active')->latest()->get();

        return view('admin.nfc_tags.create', compact('clients', 'digitalCards', 'qrCodes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'client_id' => 'nullable|exists:clients,id',
            'target_type' => 'required|in:url,digital_card,qr_code',
            'target_url' => 'nullable|url|max:1000',
            'digital_card_id' => 'nullable|exists:digital_cards,id',
            'qr_code_id' => 'nullable|exists:qr_codes,id',
            'status' => 'required|in:active,inactive,locked',
            'notes' => 'nullable|string|max:1000',
        ]);

        $tagUid = NfcTag::generateUniqueUid();

        $tag = NfcTag::create([
            'client_id' => $validated['client_id'] ?? null,
            'created_by' => auth('web')->id(),
            'tag_uid' => $tagUid,
            'name' => $validated['name'],
            'target_type' => $validated['target_type'],
            'target_url' => $validated['target_url'] ?? null,
            'digital_card_id' => $validated['digital_card_id'] ?? null,
            'qr_code_id' => $validated['qr_code_id'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        ActivityLog::log(
            action: 'nfc_tag_created',
            resourceType: 'NfcTag',
            resourceId: $tag->id,
            details: ['name' => $tag->name, 'tag_uid' => $tag->tag_uid, 'target_type' => $tag->target_type]
        );

        return redirect()->route('admin.nfc-tags.index')->with('success', "NFC Tag '{$tag->name}' ({$tag->tag_uid}) enregistré avec succès !");
    }

    public function show(NfcTag $nfcTag)
    {
        $nfcTag->load(['client', 'digitalCard', 'qrCode', 'taps' => fn ($q) => $q->latest()->take(30)]);

        return view('admin.nfc_tags.show', compact('nfcTag'));
    }

    public function edit(NfcTag $nfcTag)
    {
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();
        $digitalCards = DigitalCard::where('status', 'active')->latest()->get();
        $qrCodes = QrCode::where('status', 'active')->latest()->get();

        return view('admin.nfc_tags.edit', compact('nfcTag', 'clients', 'digitalCards', 'qrCodes'));
    }

    public function update(Request $request, NfcTag $nfcTag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'client_id' => 'nullable|exists:clients,id',
            'target_type' => 'required|in:url,digital_card,qr_code',
            'target_url' => 'nullable|url|max:1000',
            'digital_card_id' => 'nullable|exists:digital_cards,id',
            'qr_code_id' => 'nullable|exists:qr_codes,id',
            'status' => 'required|in:active,inactive,locked',
            'notes' => 'nullable|string|max:1000',
        ]);

        $nfcTag->update($validated);

        ActivityLog::log(
            action: 'nfc_tag_updated',
            resourceType: 'NfcTag',
            resourceId: $nfcTag->id,
            details: ['name' => $nfcTag->name]
        );

        return redirect()->route('admin.nfc-tags.index')->with('success', "NFC Tag '{$nfcTag->name}' mis à jour avec succès !");
    }

    public function destroy(NfcTag $nfcTag)
    {
        $name = $nfcTag->name;
        $nfcTag->delete();

        ActivityLog::log(
            action: 'nfc_tag_deleted',
            resourceType: 'NfcTag',
            resourceId: $nfcTag->id,
            details: ['name' => $name]
        );

        return redirect()->route('admin.nfc-tags.index')->with('success', "NFC Tag {$name} supprimé.");
    }
}
