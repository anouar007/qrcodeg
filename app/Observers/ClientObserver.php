<?php

namespace App\Observers;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ClientObserver
{
    public function created(Client $client): void
    {
        $client->clientHistories()->create([
            'user_id' => Auth::id(),
            'action_type' => 'client_created',
            'description' => "Client profile '{$client->name}' created in project '{$client->project_name}'.",
            'metadata' => [
                'city' => $client->city,
                'phone' => $client->phone,
                'status' => $client->status,
            ],
            'created_at' => now(),
        ]);
    }

    public function updated(Client $client): void
    {
        $dirty = $client->getDirty();
        unset($dirty['updated_at'], $dirty['remember_token']);

        if (! empty($dirty)) {
            $changedFields = implode(', ', array_keys($dirty));
            $client->clientHistories()->create([
                'user_id' => Auth::id(),
                'action_type' => 'client_updated',
                'description' => "Profile updated: {$client->name} ({$changedFields}).",
                'metadata' => [
                    'changes' => $dirty,
                ],
                'created_at' => now(),
            ]);
        }
    }

    public function deleted(Client $client): void
    {
        $client->clientHistories()->create([
            'user_id' => Auth::id(),
            'action_type' => 'client_deleted',
            'description' => "Client '{$client->name}' was deleted or archived.",
            'metadata' => [
                'deleted_at' => now()->toIso8601String(),
            ],
            'created_at' => now(),
        ]);
    }
}
