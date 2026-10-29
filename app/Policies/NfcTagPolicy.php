<?php

namespace App\Policies;

use App\Models\NfcTag;
use App\Models\User;

class NfcTagPolicy
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
        return $user->hasPermission('view_nfc_tags');
    }

    public function view(User $user, NfcTag $nfcTag): bool
    {
        return $user->hasPermission('view_nfc_tags');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create_nfc_tags');
    }

    public function update(User $user, NfcTag $nfcTag): bool
    {
        return $user->hasPermission('edit_nfc_tags');
    }

    public function delete(User $user, NfcTag $nfcTag): bool
    {
        return $user->hasPermission('delete_nfc_tags');
    }
}
