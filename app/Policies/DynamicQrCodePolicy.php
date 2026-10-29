<?php

namespace App\Policies;

use App\Models\DynamicQrCode;
use App\Models\User;

class DynamicQrCodePolicy
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
        return $user->hasPermission('view_qr');
    }

    public function view(User $user, DynamicQrCode $dynamicQrCode): bool
    {
        return $user->hasPermission('view_qr');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create_qr');
    }

    public function update(User $user, DynamicQrCode $dynamicQrCode): bool
    {
        return $user->hasPermission('edit_qr');
    }

    public function redirectManage(User $user, ?DynamicQrCode $dynamicQrCode = null): bool
    {
        return $user->hasPermission('redirect_manage_qr') || $user->hasPermission('edit_qr');
    }

    public function delete(User $user, DynamicQrCode $dynamicQrCode): bool
    {
        return $user->hasPermission('delete_qr');
    }

    public function export(User $user, ?DynamicQrCode $dynamicQrCode = null): bool
    {
        return $user->hasPermission('view_qr');
    }
}
