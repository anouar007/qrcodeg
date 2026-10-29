<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_clients');
    }

    public function view(User $user, Client $client): bool
    {
        return $user->hasPermission('view_clients');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create_clients');
    }

    public function update(User $user, Client $client): bool
    {
        return $user->hasPermission('edit_clients');
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->hasPermission('delete_clients');
    }

    public function export(User $user): bool
    {
        return $user->hasPermission('view_clients');
    }
}
