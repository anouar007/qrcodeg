<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientHistory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientService
{
    /**
     * Create a new client record.
     */
    public function createClient(array $data, ?UploadedFile $logo = null): Client
    {
        return DB::transaction(function () use ($data, $logo) {
            $name = $data['name'] ?? ($data['contact_name'] ?? ($data['company_name'] ?? 'Client'));
            $contactName = $data['contact_name'] ?? $name;
            $companyName = $data['company_name'] ?? ($data['project_name'] ?: $name);
            $projectName = $data['project_name'] ?? null;
            $email = ! empty($data['email'])
                ? $data['email']
                : (strtolower(Str::slug($name)).'_'.Str::random(5).'@client.local');

            $accessCode = ! empty($data['access_code'])
                ? strtoupper(trim($data['access_code']))
                : Client::generateUniqueAccessCode();

            $logoPath = null;
            if ($logo) {
                $logoPath = $logo->store('clients/logos', 'public');
            }

            $client = Client::create([
                'name' => $name,
                'contact_name' => $contactName,
                'company_name' => $companyName,
                'project_name' => $projectName,
                'email' => $email,
                'city' => $data['city'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'country' => $data['country'] ?? 'Morocco',
                'status' => $data['status'] ?? 'active',
                'notes' => $data['notes'] ?? null,
                'access_code' => $accessCode,
                'logo' => $logoPath,
                'password' => ! empty($data['password']) ? Hash::make($data['password']) : null,
                'is_password_set' => ! empty($data['password']),
            ]);

            return $client;
        });
    }

    /**
     * Update an existing client.
     */
    public function updateClient(Client $client, array $data, ?UploadedFile $logo = null): Client
    {
        return DB::transaction(function () use ($client, $data, $logo) {
            $name = $data['name'] ?? ($data['contact_name'] ?? ($data['company_name'] ?? $client->name));

            $updateData = [
                'name' => $name,
                'contact_name' => $data['contact_name'] ?? $name,
                'company_name' => $data['company_name'] ?? ($data['project_name'] ?: $client->company_name),
                'project_name' => array_key_exists('project_name', $data) ? $data['project_name'] : $client->project_name,
                'city' => array_key_exists('city', $data) ? $data['city'] : $client->city,
                'phone' => array_key_exists('phone', $data) ? $data['phone'] : $client->phone,
                'status' => $data['status'] ?? $client->status,
                'notes' => array_key_exists('notes', $data) ? $data['notes'] : $client->notes,
            ];

            if (! empty($data['email'])) {
                $updateData['email'] = $data['email'];
            }
            if (isset($data['address'])) {
                $updateData['address'] = $data['address'];
            }
            if (isset($data['country'])) {
                $updateData['country'] = $data['country'];
            }
            if (! empty($data['access_code'])) {
                $updateData['access_code'] = strtoupper(trim($data['access_code']));
            }
            if (! empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
                $updateData['is_password_set'] = true;
            }

            if ($logo) {
                if ($client->logo) {
                    Storage::disk('public')->delete($client->logo);
                }
                $updateData['logo'] = $logo->store('clients/logos', 'public');
            }

            $client->update($updateData);

            return $client;
        });
    }

    /**
     * Delete a client and soft-delete/detach associated models.
     */
    public function deleteClient(Client $client): bool
    {
        return (bool) $client->delete();
    }

    /**
     * Log a manual or service-level history entry on a client.
     */
    public function recordHistory(Client $client, string $actionType, string $description, array $metadata = []): ClientHistory
    {
        return $client->clientHistories()->create([
            'user_id' => Auth::id(),
            'action_type' => $actionType,
            'description' => $description,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }

    /**
     * Reset a client's access code.
     */
    public function resetAccessCode(Client $client): string
    {
        $newCode = Client::generateUniqueAccessCode();
        $client->update([
            'access_code' => $newCode,
            'password' => null,
            'is_password_set' => false,
        ]);

        $this->recordHistory($client, 'access_code_reset', "Access code reset to {$newCode}.", [
            'new_access_code' => $newCode,
        ]);

        return $newCode;
    }

    /**
     * Export clients as a streamed CSV file.
     */
    public function exportCsv(array $filters = []): StreamedResponse
    {
        $query = Client::withCount(['digitalCards', 'nfcTags', 'dynamicQrCodes', 'qrCodes']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
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

        if (! empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $clients = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="clients_export_'.now()->format('Y_m_d_His').'.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($clients) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Client Name',
                'Project Name',
                'Company Name',
                'City',
                'Phone',
                'Email',
                'Status',
                'Access Code',
                'Digital Cards Count',
                'NFC Tags Count',
                'Dynamic QR Count',
                'Created At',
            ]);

            foreach ($clients as $client) {
                fputcsv($handle, [
                    $client->id,
                    $client->name,
                    $client->project_name ?? '',
                    $client->company_name ?? '',
                    $client->city ?? '',
                    $client->phone ?? '',
                    $client->email ?? '',
                    $client->status,
                    $client->access_code ?? '',
                    $client->digital_cards_count ?? 0,
                    $client->nfc_tags_count ?? 0,
                    ($client->dynamic_qr_codes_count ?? 0) + ($client->qr_codes_count ?? 0),
                    $client->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
