<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use App\Models\NfcTap;
use App\Models\QrCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NfcTagController extends Controller
{
    /**
     * Public NFC Tap & Redirection Endpoint (/nfc/{id_or_uid})
     * Resolves the NFC tag by numeric ID or hardware/tag UID, logs telemetry (ScanLog), and redirects instantly (302) to destination URL.
     */
    public function redirect(Request $request, string $id_or_uid): RedirectResponse|Response
    {
        $tag = is_numeric($id_or_uid)
            ? NfcTag::where('id', (int) $id_or_uid)->orWhere('tag_uid', $id_or_uid)->first()
            : NfcTag::where('tag_uid', $id_or_uid)->first();

        if (! $tag) {
            return response()->view('qr.unavailable', [
                'title' => 'NFC Tag Not Found',
                'message' => "The NFC chip identifier '{$id_or_uid}' is not recognized or does not exist.",
            ], 404);
        }

        if ($tag->status !== 'active') {
            return response()->view('qr.unavailable', [
                'title' => 'NFC Tag Unavailable',
                'message' => "This NFC tag ({$tag->tag_uid}) is currently {$tag->status}.",
            ], 200);
        }

        // Record telemetry: polymorphic ScanLog entry (IP, user agent, timestamp) and increment scans_count
        $tag->recordScan($request->ip(), $request->userAgent());

        // Also record NfcTap if model exists for backwards compatibility
        if (class_exists(NfcTap::class)) {
            NfcTap::create([
                'nfc_tag_id' => $tag->id,
                'tapped_at' => now(),
                'ip_hash' => hash('sha256', ($request->ip() ?: '127.0.0.1').config('app.key')),
                'device_type' => 'nfc_mobile',
                'operating_system' => 'auto',
                'browser' => 'auto',
                'referrer' => $request->header('referer'),
                'user_agent' => substr($request->userAgent() ?? '', 0, 500),
            ]);
        }

        $destUrl = trim($tag->destination_url ?: $tag->target_url);

        // Normalize URL scheme
        if (! empty($destUrl) && ! preg_match('#^[a-zA-Z][a-zA-Z0-9+.-]*://#', $destUrl) && ! str_starts_with($destUrl, 'tel:') && ! str_starts_with($destUrl, 'mailto:')) {
            $destUrl = 'https://'.$destUrl;
        }

        if (empty($destUrl)) {
            return response()->view('qr.unavailable', [
                'title' => 'No Destination Configured',
                'message' => 'This NFC tag does not have an active destination URL configured.',
            ], 200);
        }

        return redirect()->away($destUrl, 302);
    }

    /**
     * List View: Table with UID, assigned client, label, destination URL, scan count, status toggle, and creation date
     */
    public function index(Request $request)
    {
        $query = NfcTag::with(['client', 'digitalCard', 'qrCode', 'scanLogs']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('tag_uid', 'like', "%{$search}%")
                    ->orWhere('destination_url', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%")
                    ->orWhereHas('client', fn ($cq) => $cq->where('name', 'like', "%{$search}%")->orWhere('project_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->input('assigned') === 'assigned') {
            $query->whereNotNull('client_id');
        } elseif ($request->input('assigned') === 'unassigned') {
            $query->whereNull('client_id');
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest', 'date_asc' => $query->oldest(),
            'scans_high', 'scans_desc' => $query->orderByDesc('scans_count'),
            'scans_low' => $query->orderBy('scans_count'),
            'label' => $query->orderBy('label'),
            'uid', 'code_asc' => $query->orderBy('tag_uid'),
            default => $query->latest(),
        };

        $nfcTags = $query->paginate(20)->withQueryString();
        $clients = Client::orderBy('name')->get();
        $digitalCards = DigitalCard::where('status', 'active')->orderBy('title')->get();
        $dynamicQrs = DynamicQrCode::where('status', 'active')->orderBy('label')->get();

        $metrics = [
            'total' => NfcTag::count(),
            'active' => NfcTag::where('status', 'active')->count(),
            'scans' => (int) (NfcTag::sum('scans_count') ?: NfcTag::sum('tap_count')),
            'clients_with_nfc' => NfcTag::whereNotNull('client_id')->distinct('client_id')->count(),
        ];

        return view('admin.nfc_tags.index', compact('nfcTags', 'clients', 'digitalCards', 'dynamicQrs', 'metrics'));
    }

    /**
     * Export NFC tags list to CSV format
     */
    public function exportCsv(): Response
    {
        $tags = NfcTag::with('client')->latest()->get();
        $csv = "Code,Statut,Client,Profil / Lien direct,Scans,Date\n";
        foreach ($tags as $t) {
            $code = $t->tag_uid ?: ('YN33-'.str_pad((string) $t->id, 6, '0', STR_PAD_LEFT));
            $dest = $t->destination_url ?: $t->target_url;
            $client = $t->client?->name ?? '—';
            $csv .= "\"{$code}\",\"{$t->status}\",\"{$client}\",\"{$dest}\",{$t->scans_count},\"{$t->created_at}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="cartes_nfc_'.date('Ymd_His').'.csv"',
        ]);
    }

    /**
     * Dedicated create form view
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $digitalCards = DigitalCard::where('status', 'active')->latest()->get();
        $dynamicQrs = DynamicQrCode::where('status', 'active')->latest()->get();
        $qrCodes = QrCode::where('status', 'active')->latest()->get();

        return view('admin.nfc_tags.create', compact('clients', 'digitalCards', 'dynamicQrs', 'qrCodes'));
    }

    /**
     * Register a new physical NFC chip
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:150',
            'name' => 'nullable|string|max:150',
            'tag_uid' => 'nullable|string|max:64|unique:nfc_tags,tag_uid',
            'destination_url' => 'nullable|string|max:1000',
            'target_url' => 'nullable|string|max:1000',
            'preset_target' => 'nullable|string|max:255',
            'target_type' => 'nullable|string|in:url,digital_card,qr_code',
            'client_id' => 'nullable|exists:clients,id',
            'digital_card_id' => 'nullable|exists:digital_cards,id',
            'qr_code_id' => 'nullable|exists:qr_codes,id',
            'status' => 'nullable|in:active,inactive,locked',
            'notes' => 'nullable|string|max:1000',
        ]);

        $label = $validated['label'] ?? ($validated['name'] ?? 'NFC Tag');
        $tagUid = ! empty($validated['tag_uid'])
            ? trim($validated['tag_uid'])
            : NfcTag::generateUniqueUid();

        // Resolve destination URL from preset dropdown if provided
        $destUrl = $validated['destination_url'] ?? ($validated['target_url'] ?? null);
        $digitalCardId = $validated['digital_card_id'] ?? null;
        $qrCodeId = $validated['qr_code_id'] ?? null;
        $targetType = $validated['target_type'] ?? 'url';

        if (! empty($validated['preset_target'])) {
            $preset = $validated['preset_target'];
            if (str_starts_with($preset, 'card:')) {
                $cardId = (int) substr($preset, 5);
                $card = DigitalCard::find($cardId);
                if ($card) {
                    $destUrl = route('digital-cards.public.show', $card->slug);
                    $targetType = 'digital_card';
                    $digitalCardId = $card->id;
                }
            } elseif (str_starts_with($preset, 'qr:')) {
                $qrId = (int) substr($preset, 3);
                $dqr = DynamicQrCode::find($qrId);
                if ($dqr) {
                    $destUrl = route('qr.redirect', ['code' => $dqr->code]);
                    $targetType = 'qr_code';
                }
            } elseif ($preset !== 'custom') {
                $destUrl = $preset;
            }
        }

        // If target_type was explicitly digital_card with digital_card_id
        if ($targetType === 'digital_card' && $digitalCardId && empty($destUrl)) {
            $card = DigitalCard::find($digitalCardId);
            if ($card) {
                $destUrl = route('digital-cards.public.show', $card->slug);
            }
        }

        $tag = NfcTag::create([
            'client_id' => $validated['client_id'] ?? null,
            'created_by' => auth('web')->id(),
            'tag_uid' => $tagUid,
            'label' => $label,
            'name' => $label,
            'destination_url' => $destUrl,
            'target_url' => $destUrl,
            'target_type' => $targetType,
            'digital_card_id' => $digitalCardId,
            'qr_code_id' => $qrCodeId,
            'scans_count' => 0,
            'tap_count' => 0,
            'status' => $validated['status'] ?? 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        ActivityLog::log(
            action: 'nfc_tag_created',
            resourceType: 'NfcTag',
            resourceId: $tag->id,
            details: ['label' => $tag->label, 'tag_uid' => $tag->tag_uid, 'destination_url' => $tag->destination_url]
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "NFC Tag '{$tag->label}' ({$tag->tag_uid}) successfully registered.",
                'tag' => $tag,
            ]);
        }

        return redirect()->route('admin.nfc-tags.index')
            ->with('success', "NFC Tag '{$tag->label}' (UID: {$tag->tag_uid}) successfully configured!");
    }

    /**
     * Show NFC Tag detail and telemetry view
     */
    public function show(NfcTag $nfcTag)
    {
        $nfcTag->load([
            'client',
            'digitalCard',
            'qrCode',
            'scanLogs' => fn ($q) => $q->latest('scanned_at')->take(50),
            'taps' => fn ($q) => $q->latest('tapped_at')->take(50),
        ]);

        $clients = Client::orderBy('name')->get();
        $digitalCards = DigitalCard::where('status', 'active')->orderBy('title')->get();
        $dynamicQrs = DynamicQrCode::where('status', 'active')->orderBy('label')->get();

        return view('admin.nfc_tags.show', compact('nfcTag', 'clients', 'digitalCards', 'dynamicQrs'));
    }

    /**
     * Dedicated edit view (also serves JSON for modal editing)
     */
    public function edit(Request $request, NfcTag $nfcTag): Response|JsonResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'id' => $nfcTag->id,
                'tag_uid' => $nfcTag->tag_uid,
                'label' => $nfcTag->label ?: $nfcTag->name,
                'destination_url' => $nfcTag->destination_url ?: $nfcTag->target_url,
                'client_id' => $nfcTag->client_id,
                'status' => $nfcTag->status,
                'notes' => $nfcTag->notes,
                'target_type' => $nfcTag->target_type,
                'digital_card_id' => $nfcTag->digital_card_id,
                'qr_code_id' => $nfcTag->qr_code_id,
            ]);
        }

        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $digitalCards = DigitalCard::where('status', 'active')->latest()->get();
        $dynamicQrs = DynamicQrCode::where('status', 'active')->latest()->get();
        $qrCodes = QrCode::where('status', 'active')->latest()->get();

        return response()->view('admin.nfc_tags.edit', compact('nfcTag', 'clients', 'digitalCards', 'dynamicQrs', 'qrCodes'));
    }

    /**
     * Update NFC Tag configuration
     */
    public function update(Request $request, NfcTag $nfcTag): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:150',
            'name' => 'nullable|string|max:150',
            'tag_uid' => 'nullable|string|max:64|unique:nfc_tags,tag_uid,'.$nfcTag->id,
            'destination_url' => 'nullable|string|max:1000',
            'target_url' => 'nullable|string|max:1000',
            'preset_target' => 'nullable|string|max:255',
            'target_type' => 'nullable|string|in:url,digital_card,qr_code',
            'client_id' => 'nullable|exists:clients,id',
            'digital_card_id' => 'nullable|exists:digital_cards,id',
            'qr_code_id' => 'nullable|exists:qr_codes,id',
            'status' => 'required|in:active,inactive,locked',
            'notes' => 'nullable|string|max:1000',
        ]);

        $label = $validated['label'] ?? ($validated['name'] ?? $nfcTag->label);
        $destUrl = $validated['destination_url'] ?? ($validated['target_url'] ?? $nfcTag->destination_url);
        $digitalCardId = $validated['digital_card_id'] ?? $nfcTag->digital_card_id;
        $qrCodeId = $validated['qr_code_id'] ?? $nfcTag->qr_code_id;
        $targetType = $validated['target_type'] ?? ($nfcTag->target_type ?: 'url');

        if (! empty($validated['preset_target'])) {
            $preset = $validated['preset_target'];
            if (str_starts_with($preset, 'card:')) {
                $cardId = (int) substr($preset, 5);
                $card = DigitalCard::find($cardId);
                if ($card) {
                    $destUrl = route('digital-cards.public.show', $card->slug);
                    $targetType = 'digital_card';
                    $digitalCardId = $card->id;
                }
            } elseif (str_starts_with($preset, 'qr:')) {
                $qrId = (int) substr($preset, 3);
                $dqr = DynamicQrCode::find($qrId);
                if ($dqr) {
                    $destUrl = route('qr.redirect', ['code' => $dqr->code]);
                    $targetType = 'qr_code';
                }
            } elseif ($preset !== 'custom') {
                $destUrl = $preset;
            }
        }

        $updateData = [
            'label' => $label,
            'name' => $label,
            'client_id' => $validated['client_id'] ?? null,
            'destination_url' => $destUrl,
            'target_url' => $destUrl,
            'target_type' => $targetType,
            'digital_card_id' => $digitalCardId,
            'qr_code_id' => $qrCodeId,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ];

        if (! empty($validated['tag_uid'])) {
            $updateData['tag_uid'] = trim($validated['tag_uid']);
        }

        $nfcTag->update($updateData);

        ActivityLog::log(
            action: 'nfc_tag_updated',
            resourceType: 'NfcTag',
            resourceId: $nfcTag->id,
            details: ['label' => $nfcTag->label, 'destination_url' => $nfcTag->destination_url]
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "NFC Tag '{$nfcTag->label}' updated.",
                'tag' => $nfcTag,
            ]);
        }

        return redirect()->route('admin.nfc-tags.index')
            ->with('success', "NFC Tag '{$nfcTag->label}' updated successfully!");
    }

    /**
     * Quick Swap Destination URL
     * Instant target modification when a client changes campaigns
     */
    public function swapDestination(Request $request, NfcTag $nfcTag): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'destination_url' => 'nullable|string|max:1000',
            'preset_target' => 'nullable|string|max:255',
        ]);

        $destUrl = $validated['destination_url'] ?? null;
        $targetType = 'url';
        $digitalCardId = null;
        $qrCodeId = null;

        if (! empty($validated['preset_target'])) {
            $preset = $validated['preset_target'];
            if (str_starts_with($preset, 'card:')) {
                $cardId = (int) substr($preset, 5);
                $card = DigitalCard::find($cardId);
                if ($card) {
                    $destUrl = route('digital-cards.public.show', $card->slug);
                    $targetType = 'digital_card';
                    $digitalCardId = $card->id;
                }
            } elseif (str_starts_with($preset, 'qr:')) {
                $qrId = (int) substr($preset, 3);
                $dqr = DynamicQrCode::find($qrId);
                if ($dqr) {
                    $destUrl = route('qr.redirect', ['code' => $dqr->code]);
                    $targetType = 'qr_code';
                }
            } elseif ($preset !== 'custom') {
                $destUrl = $preset;
            }
        }

        if (empty($destUrl)) {
            return back()->withErrors(['destination_url' => 'Please provide a destination URL or select a preset target.']);
        }

        $oldUrl = $nfcTag->destination_url;
        $nfcTag->update([
            'destination_url' => $destUrl,
            'target_url' => $destUrl,
            'target_type' => $targetType,
            'digital_card_id' => $digitalCardId,
            'qr_code_id' => $qrCodeId,
        ]);

        $msg = "NFC Tag destination swapped to {$nfcTag->destination_url}. Physical chip remains active!";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'destination_url' => $nfcTag->destination_url,
                'old_destination_url' => $oldUrl,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Toggle status (active <-> inactive)
     */
    public function toggleStatus(Request $request, NfcTag $nfcTag): RedirectResponse|JsonResponse
    {
        $newStatus = $request->input('status') ?: ($nfcTag->status === 'active' ? 'inactive' : 'active');

        $nfcTag->update(['status' => $newStatus]);

        ActivityLog::log(
            action: 'nfc_tag_status_toggled',
            resourceType: 'NfcTag',
            resourceId: $nfcTag->id,
            details: ['status' => $newStatus]
        );

        $msg = "NFC Tag '{$nfcTag->label}' status changed to {$newStatus}.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'status' => $nfcTag->status,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Soft delete NFC tag
     */
    public function destroy(NfcTag $nfcTag): RedirectResponse
    {
        $label = $nfcTag->label;
        $nfcTag->delete();

        ActivityLog::log(
            action: 'nfc_tag_deleted',
            resourceType: 'NfcTag',
            resourceId: $nfcTag->id,
            details: ['label' => $label]
        );

        return redirect()->route('admin.nfc-tags.index')
            ->with('success', "NFC Tag '{$label}' deleted successfully.");
    }
}
