<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'username',
        'email',
        'phone',
        'password',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (User $user) {
            if (! empty($user->name) && empty($user->first_name) && empty($user->last_name)) {
                $parts = explode(' ', trim($user->name), 2);
                $user->first_name = $parts[0] ?? '';
                $user->last_name = $parts[1] ?? '';
            } elseif (empty($user->name) && (! empty($user->first_name) || ! empty($user->last_name))) {
                $user->name = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
            }

            if (empty($user->username) && ! empty($user->email)) {
                $user->username = explode('@', $user->email)[0];
            }
        });
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?: trim(($this->first_name ?? '').' '.($this->last_name ?? '')),
            set: fn ($value) => [
                'name' => $value,
                'first_name' => explode(' ', trim($value ?? ''), 2)[0] ?? '',
                'last_name' => explode(' ', trim($value ?? ''), 2)[1] ?? '',
            ]
        );
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function directPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    public function permissions(): BelongsToMany
    {
        return $this->directPermissions();
    }

    public function digitalCards(): HasMany
    {
        return $this->hasMany(DigitalCard::class, 'user_id');
    }

    public function assignedMenus(): BelongsToMany
    {
        return $this->belongsToMany(RestaurantMenu::class, 'restaurant_menu_user')->withTimestamps();
    }

    public function assignedDigitalCards(): BelongsToMany
    {
        return $this->belongsToMany(DigitalCard::class, 'digital_card_user')->withTimestamps();
    }

    public function clientHistories(): HasMany
    {
        return $this->hasMany(ClientHistory::class, 'user_id');
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class, 'created_by');
    }

    public function qrDocuments(): HasMany
    {
        return $this->hasMany(QrDocument::class, 'created_by');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function hasRole(string $roleSlug): bool
    {
        return $this->roles->contains('slug', $roleSlug);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin')
            || str_starts_with((string) $this->email, 'admin@')
            || $this->username === 'admin';
    }

    /**
     * Check if the user only holds the minimalist QR Link Editor role.
     */
    public function isQrLinkEditorOnly(): bool
    {
        return ! $this->isAdmin()
            && $this->hasRole('qr_link_editor')
            && $this->roles->count() === 1;
    }

    /**
     * Check if the user only holds the minimalist Menu & Card Scanner role.
     */
    public function isMenuCardScannerOnly(): bool
    {
        return ! $this->isAdmin()
            && $this->hasRole('menu_card_scanner')
            && $this->roles->count() === 1;
    }

    /**
     * Check if the user is restricted to editing only their assigned menus or digital cards.
     */
    public function hasAssignedAssetsOnly(): bool
    {
        if ($this->isAdmin()) {
            return false;
        }

        $hasAssigned = $this->assignedMenus()->exists() || $this->assignedDigitalCards()->exists();

        return $hasAssigned && ! $this->hasAnyPermission([
            'view_dashboard',
            'view_clients',
            'view_users',
            'manage_roles',
            'manage_settings',
        ]);
    }

    public function canAccessMenu(RestaurantMenu $menu): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->hasPermission('scan_edit_menu_card') && in_array($menu->id, session('scanned_menu_ids', []), true)) {
            return true;
        }

        if ($this->assignedMenus()->exists()) {
            return $this->assignedMenus()->where('restaurant_menus.id', $menu->id)->exists();
        }

        return ! $this->hasAssignedAssetsOnly() && ! $this->isMenuCardScannerOnly();
    }

    public function canAccessDigitalCard(DigitalCard $card): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->hasPermission('scan_edit_menu_card') && in_array($card->id, session('scanned_card_ids', []), true)) {
            return true;
        }

        if ($this->assignedDigitalCards()->where('digital_cards.id', $card->id)->exists()) {
            return true;
        }

        return $this->hasPermission('edit_digital_cards') && ! $this->assignedDigitalCards()->exists();
    }

    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->directPermissions->contains('slug', $permissionSlug)) {
            return true;
        }

        return $this->roles->flatMap->permissions->contains('slug', $permissionSlug);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return in_array($this->status, ['suspended', 'inactive'], true);
    }
}
