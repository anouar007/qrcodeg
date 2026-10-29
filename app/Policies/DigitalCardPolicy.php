<?php

namespace App\Policies;

use App\Models\DigitalCard;
use App\Models\User;

class DigitalCardPolicy
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
        return $user->hasPermission('view_digital_cards');
    }

    public function view(User $user, DigitalCard $digitalCard): bool
    {
        return $user->hasPermission('view_digital_cards');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create_digital_cards');
    }

    public function update(User $user, DigitalCard $digitalCard): bool
    {
        return $user->hasPermission('edit_digital_cards');
    }

    public function delete(User $user, DigitalCard $digitalCard): bool
    {
        return $user->hasPermission('delete_digital_cards');
    }

    public function export(User $user, ?DigitalCard $digitalCard = null): bool
    {
        return $user->hasPermission('export_digital_cards') || $user->hasPermission('view_digital_cards');
    }
}
