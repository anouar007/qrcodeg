<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::withCount(['qrCodes', 'activityLogs']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('access_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        $suggestedCode = Client::generateUniqueAccessCode();

        return view('admin.clients.create', compact('suggestedCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:150',
            'contact_name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:clients,email',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:1000',
            'access_code' => 'nullable|string|max:64|unique:clients,access_code',
            'password' => 'nullable|string|min:6',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $accessCode = ! empty($validated['access_code'])
            ? strtoupper(trim($validated['access_code']))
            : Client::generateUniqueAccessCode();

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('clients/logos', 'public');
        }

        $client = Client::create([
            'company_name' => $validated['company_name'],
            'contact_name' => $validated['contact_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'access_code' => $accessCode,
            'logo' => $logoPath,
            'password' => ! empty($validated['password']) ? Hash::make($validated['password']) : null,
            'is_password_set' => ! empty($validated['password']),
        ]);

        ActivityLog::log(
            action: 'client_created',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['company_name' => $client->company_name, 'access_code' => $client->access_code]
        );

        return redirect()->route('admin.clients.show', $client)->with('success', "Client {$client->company_name} created successfully with access code {$client->access_code}.");
    }

    public function show(Client $client)
    {
        $client->load(['qrCodes.scans', 'qrCodes.content', 'qrCodes.design']);
        $totalScans = $client->qrCodes->sum('scan_count');
        $availableQrs = QrCode::whereNull('client_id')->get();
        $recentLogs = $client->activityLogs()->latest()->take(10)->get();

        return view('admin.clients.show', compact('client', 'totalScans', 'availableQrs', 'recentLogs'));
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:150',
            'contact_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:1000',
            'access_code' => ['nullable', 'string', 'max:64', Rule::unique('clients')->ignore($client->id)],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $updateData = [
            'company_name' => $validated['company_name'],
            'contact_name' => $validated['contact_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ];

        if (! empty($validated['access_code'])) {
            $updateData['access_code'] = strtoupper(trim($validated['access_code']));
        }

        if ($request->hasFile('logo')) {
            if ($client->logo) {
                Storage::disk('public')->delete($client->logo);
            }
            $updateData['logo'] = $request->file('logo')->store('clients/logos', 'public');
        }

        $client->update($updateData);

        ActivityLog::log(
            action: 'client_updated',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['company_name' => $client->company_name]
        );

        return redirect()->route('admin.clients.show', $client)->with('success', "Client {$client->company_name} updated successfully.");
    }

    public function assignQr(Request $request, Client $client)
    {
        $validated = $request->validate([
            'qr_code_id' => 'required|exists:qr_codes,id',
        ]);

        $qr = QrCode::findOrFail($validated['qr_code_id']);
        $qr->update(['client_id' => $client->id]);

        ActivityLog::log(
            action: 'qr_assigned',
            resourceType: 'QrCode',
            resourceId: $qr->id,
            details: ['client_id' => $client->id, 'client_name' => $client->company_name]
        );

        return back()->with('success', "QR Code {$qr->name} ({$qr->public_code}) assigned to {$client->company_name}.");
    }

    public function resetAccess(Client $client)
    {
        $newCode = Client::generateUniqueAccessCode();
        $client->update([
            'access_code' => $newCode,
            'password' => null,
            'is_password_set' => false,
        ]);

        ActivityLog::log(
            action: 'client_access_reset',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['new_access_code' => $newCode]
        );

        return back()->with('success', "Access code reset! New code: {$newCode}. Client must set password on next login.");
    }

    public function toggleStatus(Client $client)
    {
        $newStatus = $client->status === 'active' ? 'inactive' : 'active';
        $client->update(['status' => $newStatus]);

        ActivityLog::log(
            action: $newStatus === 'active' ? 'client_activated' : 'client_deactivated',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['status' => $newStatus]
        );

        return back()->with('success', "Client {$client->company_name} status updated to {$newStatus}.");
    }

    public function destroy(Client $client)
    {
        $name = $client->company_name;
        $client->delete();

        ActivityLog::log(
            action: 'client_deleted',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['company_name' => $name]
        );

        return redirect()->route('admin.clients.index')->with('success', "Client {$name} removed.");
    }
}
