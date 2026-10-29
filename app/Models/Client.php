<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Client extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'city',
        'phone',
        'project_name',
        'status',
        'notes',
        // Additional portal & legacy attributes
        'company_name',
        'contact_name',
        'email',
        'address',
        'country',
        'logo',
        'access_code',
        'password',
        'is_password_set',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_password_set' => 'boolean',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Client $client) {
            if (empty($client->name)) {
                $client->name = $client->contact_name ?: ($client->company_name ?: 'Client');
            }
            if (empty($client->company_name) && ! empty($client->name)) {
                $client->company_name = $client->name;
            }
            if (empty($client->contact_name) && ! empty($client->name)) {
                $client->contact_name = $client->name;
            }
        });
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?: ($this->contact_name ?: ($this->company_name ?: 'Client #'.$this->id)),
            set: fn ($value) => [
                'name' => $value,
                'company_name' => $this->company_name ?: $value,
                'contact_name' => $this->contact_name ?: $value,
            ]
        );
    }

    /**
     * Exact required Eloquent relationships
     */
    public function digitalCards(): HasMany
    {
        return $this->hasMany(DigitalCard::class);
    }

    public function nfcTags(): HasMany
    {
        return $this->hasMany(NfcTag::class);
    }

    public function dynamicQrCodes(): HasMany
    {
        return $this->hasMany(DynamicQrCode::class);
    }

    public function clientHistories(): HasMany
    {
        return $this->hasMany(ClientHistory::class)->orderBy('created_at', 'desc');
    }

    public function histories(): HasMany
    {
        return $this->clientHistories();
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function qrDocuments(): HasMany
    {
        return $this->hasMany(QrDocument::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Helper method to automatically log client history on relevant actions
     */
    public function logHistory(string $actionType, string $description, ?array $metadata = null, ?int $userId = null): ClientHistory
    {
        return $this->clientHistories()->create([
            'user_id' => $userId ?? Auth::id(),
            'action_type' => $actionType,
            'description' => $description,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }

    public static function generateUniqueAccessCode(): string
    {
        do {
            $part1 = strtoupper(Str::random(4));
            $part2 = strtoupper(Str::random(4));
            $part3 = strtoupper(Str::random(4));
            $code = "{$part1}-{$part2}-{$part3}";
        } while (self::where('access_code', $code)->exists() || QrCode::where('access_code', $code)->exists());

        return $code;
    }
}
