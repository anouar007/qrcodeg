<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NfcTag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'created_by',
        'tag_uid',
        'name',
        'target_type',
        'target_url',
        'digital_card_id',
        'qr_code_id',
        'tap_count',
        'last_tapped_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'tap_count' => 'integer',
            'last_tapped_at' => 'datetime',
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

    public function digitalCard(): BelongsTo
    {
        return $this->belongsTo(DigitalCard::class);
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }

    public function taps(): HasMany
    {
        return $this->hasMany(NfcTap::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getPublicUrlAttribute(): string
    {
        return url('/n/'.$this->tag_uid);
    }

    /**
     * Resolve destination link
     */
    public function getDestinationUrlAttribute(): string
    {
        if ($this->target_type === 'digital_card' && $this->digitalCard) {
            return $this->digitalCard->public_url;
        }

        if ($this->target_type === 'qr_code' && $this->qrCode) {
            return $this->qrCode->dynamic_url;
        }

        return $this->target_url ?: url('/');
    }

    public static function generateUniqueUid(): string
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charLength = strlen($characters);

        do {
            $randomString = '';
            $bytes = random_bytes(6);
            for ($i = 0; $i < 6; $i++) {
                $randomString .= $characters[ord($bytes[$i]) % $charLength];
            }
            $uid = 'NFC-'.$randomString;
        } while (self::where('tag_uid', $uid)->exists());

        return $uid;
    }
}
