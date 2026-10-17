<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_code',
        'access_code',
        'name',
        'description',
        'client_id',
        'type',
        'status',
        'dynamic_url',
        'password',
        'is_password_set',
        'created_by',
        'scan_count',
        'last_scanned_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'is_password_set' => 'boolean',
            'last_scanned_at' => 'datetime',
            'scan_count' => 'integer',
            'password' => 'hashed',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function content(): HasOne
    {
        return $this->hasOne(QrContent::class);
    }

    public function design(): HasOne
    {
        return $this->hasOne(QrDesign::class);
    }

    public function scans(): HasMany
    {
        return $this->hasMany(QrScan::class);
    }

    public function documentItems(): HasMany
    {
        return $this->hasMany(QrDocumentItem::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Generate unique cryptographically secure public code, e.g. QR-X7K9P2M4
     */
    public static function generateUniquePublicCode(): string
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charLength = strlen($characters);

        do {
            $randomString = '';
            $bytes = random_bytes(8);
            for ($i = 0; $i < 8; $i++) {
                $randomString .= $characters[ord($bytes[$i]) % $charLength];
            }
            $code = 'QR-'.$randomString;
        } while (self::where('public_code', $code)->exists());

        return $code;
    }

    /**
     * Generate unique cryptographically secure access code, e.g. X7K9-P2M4-81QZ
     */
    public static function generateUniqueAccessCode(): string
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charLength = strlen($characters);

        do {
            $parts = [];
            for ($p = 0; $p < 3; $p++) {
                $segment = '';
                $bytes = random_bytes(4);
                for ($i = 0; $i < 4; $i++) {
                    $segment .= $characters[ord($bytes[$i]) % $charLength];
                }
                $parts[] = $segment;
            }
            $code = implode('-', $parts);
        } while (self::where('access_code', $code)->exists() || Client::where('access_code', $code)->exists());

        return $code;
    }
}
