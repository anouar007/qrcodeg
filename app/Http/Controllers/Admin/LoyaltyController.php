<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoyaltyController extends Controller
{
    /**
     * Display the global loyalty system dashboard & cards list across all clients.
     */
    public function index(Request $request): View
    {
        // 1. Only clients who are enrolled in the loyalty program (or already have loyalty cards)
        $clients = Client::where('loyalty_enabled', true)
            ->orWhereHas('loyaltyCards')
            ->withCount('loyaltyCards')
            ->orderBy('company_name')
            ->get();

        // 2. Clients not yet enrolled in loyalty (for the "+ Ajouter un client" picker)
        $availableClients = Client::where('loyalty_enabled', false)
            ->whereDoesntHave('loyaltyCards')
            ->orderBy('company_name')
            ->get();

        // 3. High-level platform statistics
        $stats = [
            'total_cards' => LoyaltyCard::count(),
            'active_cards' => LoyaltyCard::where('status', 'active')->count(),
            'total_points' => (int) LoyaltyCard::sum('points_balance'),
            'total_transactions' => LoyaltyTransaction::count(),
            'total_clients' => $clients->count(),
            'total_allocated_limit' => (int) $clients->sum('loyalty_card_limit'),
        ];

        // 4. Filterable loyalty cards query
        $cardsQuery = LoyaltyCard::with(['client', 'latestTransaction']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $cardsQuery->where(function ($q) use ($search) {
                $q->where('card_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('nfc_uid', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($cq) use ($search) {
                        $cq->where('company_name', 'like', "%{$search}%")
                            ->orWhere('contact_name', 'like', "%{$search}%")
                            ->orWhere('access_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('client_id')) {
            $cardsQuery->where('client_id', $request->client_id);
        }

        if ($request->filled('tier')) {
            match ($request->tier) {
                'platinum' => $cardsQuery->where('points_balance', '>=', 1000),
                'gold' => $cardsQuery->whereBetween('points_balance', [500, 999]),
                'silver' => $cardsQuery->whereBetween('points_balance', [200, 499]),
                'bronze' => $cardsQuery->where('points_balance', '<', 200),
                default => null,
            };
        }

        if ($request->filled('status')) {
            $cardsQuery->where('status', $request->status);
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $cardsQuery->oldest(),
            'points_desc' => $cardsQuery->orderByDesc('points_balance'),
            'points_asc' => $cardsQuery->orderBy('points_balance'),
            'name_asc' => $cardsQuery->orderBy('customer_name'),
            default => $cardsQuery->latest(),
        };

        $cards = $cardsQuery->paginate(15)->withQueryString();

        // 5. Recent platform transactions
        $recentTransactions = LoyaltyTransaction::with(['loyaltyCard.client'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.loyalty.index', compact('stats', 'cards', 'clients', 'availableClients', 'recentTransactions'));
    }

    /**
     * Display a specific loyalty card's details and audit log.
     */
    public function show(LoyaltyCard $loyaltyCard): View
    {
        $loyaltyCard->load(['client.loyaltySetting']);
        $transactions = $loyaltyCard->transactions()->latest()->paginate(15);

        return view('admin.loyalty.show', compact('loyaltyCard', 'transactions'));
    }

    /**
     * Toggle loyalty card status (active / inactive).
     */
    public function toggleStatus(LoyaltyCard $loyaltyCard): RedirectResponse
    {
        $newStatus = $loyaltyCard->status === 'active' ? 'inactive' : 'active';
        $loyaltyCard->update(['status' => $newStatus]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id' => auth('web')->id(),
                'action' => 'status_toggle',
                'description' => "Changed status of Loyalty Card #{$loyaltyCard->card_code} to {$newStatus}",
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', "Le statut de la carte #{$loyaltyCard->card_code} est maintenant '{$newStatus}'.");
    }

    /**
     * Update client loyalty card generation limit and enabled status from the loyalty hub.
     */
    public function updateClientLimit(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'loyalty_card_limit' => ['required', 'integer', 'min:0', 'max:100000'],
            'loyalty_enabled' => ['required', 'boolean'],
        ]);

        $client->update([
            'loyalty_card_limit' => $validated['loyalty_card_limit'],
            'loyalty_enabled' => $validated['loyalty_enabled'],
        ]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id' => auth('web')->id(),
                'action' => 'loyalty_quota_update',
                'description' => "Updated loyalty card limit for {$client->company_name} to {$validated['loyalty_card_limit']}",
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', "Le quota de fidélité pour '{$client->company_name}' a été mis à jour.");
    }

    /**
     * Add / enroll an existing client into the loyalty program.
     */
    public function enrollClient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'loyalty_card_limit' => ['required', 'integer', 'min:1', 'max:100000'],
        ]);

        $client = Client::findOrFail($validated['client_id']);
        $client->update([
            'loyalty_enabled' => true,
            'loyalty_card_limit' => $validated['loyalty_card_limit'],
        ]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id' => auth('web')->id(),
                'action' => 'loyalty_client_enroll',
                'description' => "Enrolled client {$client->company_name} into loyalty program with {$validated['loyalty_card_limit']} cards limit",
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', "L'entreprise '{$client->company_name}' a été ajoutée au programme de fidélité avec un quota de {$validated['loyalty_card_limit']} cartes.");
    }

    /**
     * Remove / unenroll a client from the loyalty program.
     */
    public function removeClient(Client $client): RedirectResponse
    {
        $client->update(['loyalty_enabled' => false]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id' => auth('web')->id(),
                'action' => 'loyalty_client_remove',
                'description' => "Removed client {$client->company_name} from loyalty program",
                'ip_address' => request()->ip(),
            ]);
        }

        return back()->with('success', "L'entreprise '{$client->company_name}' a été retirée du programme de fidélité.");
    }
}
