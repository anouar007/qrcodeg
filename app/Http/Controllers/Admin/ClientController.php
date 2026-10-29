<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\QrCode;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function __construct(
        protected ClientService $clientService
    ) {}

    /**
     * Display a listing of clients with search, filtering, and pagination.
     */
    public function index(Request $request)
    {
        $query = Client::withCount(['qrCodes', 'dynamicQrCodes', 'digitalCards', 'nfcTags', 'clientHistories']);

        // Search by name, project, contact, city, phone, email, or access_code
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('project_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('access_code', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by status (active/inactive)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        // Distinct cities list for the filter dropdown
        $cities = Client::whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $suggestedCode = Client::generateUniqueAccessCode();

        return view('admin.clients.index', compact('clients', 'cities', 'suggestedCode'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        $suggestedCode = Client::generateUniqueAccessCode();

        return view('admin.clients.create', compact('suggestedCode'));
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:150',
            'contact_name' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:150',
            'project_name' => 'nullable|string|max:150',
            'city' => 'nullable|string|max:100',
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+?[0-9]{1,4}[\s\-]?)?(\(?\d{1,4}\)?[\s\-]?)?[\d\s\-\.]{6,16}$/',
            ],
            'email' => 'nullable|email|max:150|unique:clients,email',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:2000',
            'access_code' => 'nullable|string|max:64|unique:clients,access_code',
            'password' => 'nullable|string|min:6',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ], [
            'phone.regex' => 'The phone number format is invalid. Please provide a valid international or local phone number (e.g. +212600112233, 0612345678, or +1-555-0199).',
        ]);

        if (empty($validated['name']) && empty($validated['contact_name']) && empty($validated['company_name'])) {
            throw ValidationException::withMessages([
                'name' => 'The client name field is required.',
            ]);
        }

        $client = $this->clientService->createClient($validated, $request->file('logo'));

        // Backward compatibility log
        ActivityLog::log(
            action: 'client_created',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['name' => $client->name, 'project' => $client->project_name, 'access_code' => $client->access_code]
        );

        return redirect()->route('admin.clients.show', $client)
            ->with('success', "Client '{$client->name}' created successfully! Access code: {$client->access_code}");
    }

    /**
     * Display the specified client with assets and timeline history.
     */
    public function show(Client $client)
    {
        $client->load([
            'digitalCards',
            'dynamicQrCodes.scanLogs',
            'nfcTags.scanLogs',
            'qrCodes.scans',
            'clientHistories.performer',
            'activityLogs' => fn ($q) => $q->latest()->take(50),
        ]);

        // Key stats for header
        $totalDynamicQrCount = $client->dynamicQrCodes->count();
        $totalLegacyQrCount = $client->qrCodes->count();
        $totalQrCodesAssigned = $totalDynamicQrCount + $totalLegacyQrCount;

        $totalQrScans = (int) ($client->dynamicQrCodes->sum('scans_count') + $client->qrCodes->sum('scan_count'));
        $totalDigitalCards = $client->digitalCards->count();
        $totalCardViews = (int) $client->digitalCards->sum('views_count');
        $totalNfcTags = $client->nfcTags->count();
        $totalNfcTaps = (int) ($client->nfcTags->sum('scans_count') ?: $client->nfcTags->sum('tap_count'));

        // Combined historic events (prioritize ClientHistory, falling back to legacy ActivityLog)
        $clientHistories = $client->clientHistories;
        $historicEvents = $client->activityLogs;

        $availableQrs = QrCode::whereNull('client_id')->get();

        // Also make views work if aliased to clients.show
        return view('admin.clients.show', compact(
            'client',
            'totalQrCodesAssigned',
            'totalQrScans',
            'totalDigitalCards',
            'totalCardViews',
            'totalNfcTags',
            'totalNfcTaps',
            'clientHistories',
            'historicEvents',
            'availableQrs'
        ));
    }

    /**
     * Show the form for editing the client.
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:150',
            'contact_name' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:150',
            'project_name' => 'nullable|string|max:150',
            'city' => 'nullable|string|max:100',
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+?[0-9]{1,4}[\s\-]?)?(\(?\d{1,4}\)?[\s\-]?)?[\d\s\-\.]{6,16}$/',
            ],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('clients')->ignore($client->id)],
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:2000',
            'access_code' => ['nullable', 'string', 'max:64', Rule::unique('clients')->ignore($client->id)],
            'password' => 'nullable|string|min:6',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ], [
            'phone.regex' => 'The phone number format is invalid. Please provide a valid international or local phone number (e.g. +212600112233, 0612345678, or +1-555-0199).',
        ]);

        if (empty($validated['name']) && empty($validated['contact_name']) && empty($validated['company_name'])) {
            throw ValidationException::withMessages([
                'name' => 'The client name field is required.',
            ]);
        }

        $this->clientService->updateClient($client, $validated, $request->file('logo'));

        ActivityLog::log(
            action: 'client_updated',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['name' => $client->name, 'company_name' => $client->company_name]
        );

        return redirect()->route('admin.clients.show', $client)
            ->with('success', "Client '{$client->name}' updated successfully.");
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        $name = $client->name;
        $this->clientService->deleteClient($client);

        ActivityLog::log(
            action: 'client_deleted',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['name' => $name]
        );

        return redirect()->route('admin.clients.index')
            ->with('success', "Client '{$name}' was successfully removed.");
    }

    /**
     * Export clients as a downloadable CSV.
     */
    public function export(Request $request)
    {
        return $this->clientService->exportCsv($request->all());
    }

    /**
     * Assign a legacy QR code to a client.
     */
    public function assignQr(Request $request, Client $client)
    {
        $validated = $request->validate([
            'qr_code_id' => 'required|exists:qr_codes,id',
        ]);

        $qr = QrCode::findOrFail($validated['qr_code_id']);
        $qr->update(['client_id' => $client->id]);

        $this->clientService->recordHistory(
            $client,
            'qr_assigned',
            "Assigned QR Code [{$qr->public_code}] '{$qr->name}'.",
            ['qr_code_id' => $qr->id, 'code' => $qr->public_code]
        );

        ActivityLog::log(
            action: 'qr_assigned',
            resourceType: 'QrCode',
            resourceId: $qr->id,
            details: ['client_id' => $client->id, 'client_name' => $client->name]
        );

        return back()->with('success', "QR Code {$qr->name} ({$qr->public_code}) assigned to {$client->name}.");
    }

    /**
     * Reset the client's access code.
     */
    public function resetAccess(Client $client)
    {
        $newCode = $this->clientService->resetAccessCode($client);

        ActivityLog::log(
            action: 'client_access_reset',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['new_access_code' => $newCode]
        );

        return back()->with('success', "Access code reset! New code: {$newCode}. Client must set password on next login.");
    }

    /**
     * Toggle client active/inactive status.
     */
    public function toggleStatus(Client $client)
    {
        $newStatus = $client->status === 'active' ? 'inactive' : 'active';
        $client->update(['status' => $newStatus]);

        $this->clientService->recordHistory(
            $client,
            $newStatus === 'active' ? 'client_activated' : 'client_deactivated',
            "Client status changed to {$newStatus}.",
            ['status' => $newStatus]
        );

        ActivityLog::log(
            action: $newStatus === 'active' ? 'client_activated' : 'client_deactivated',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['status' => $newStatus]
        );

        return back()->with('success', "Client {$client->name} status updated to {$newStatus}.");
    }
}
