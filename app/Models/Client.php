<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Client extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'company_name',
        'project_name',
        'contact_name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'logo',
        'status',
        'notes',
        'access_code',
        'password',
        'is_password_set',
        'last_login_at',
    ];

    public function getNameAttribute(): string
    {
        return $this->contact_name ?: ($this->company_name ?: 'Client #'.$this->id);
    }

    public function digitalCards(): HasMany
    {
        return $this->hasMany(DigitalCard::class);
    }

    public function nfcTags(): HasMany
    {
        return $this->hasMany(NfcTag::class);
    }

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
