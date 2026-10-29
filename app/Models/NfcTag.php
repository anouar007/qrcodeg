<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NfcTag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'tag_uid',
        'label',
        'destination_url',
        'scans_count',
        'status',
        // Legacy attributes
        'created_by',
        'name',
        'target_type',
        'target_url',
        'digital_card_id',
        'qr_code_id',
        'tap_count',
        'last_tapped_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scans_count' => 'integer',
            'tap_count' => 'integer',
            'last_tapped_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (NfcTag $tag) {
            if (empty($tag->label) && ! empty($tag->name)) {
                $tag->label = $tag->name;
            } elseif (empty($tag->name) && ! empty($tag->label)) {
                $tag->name = $tag->label;
            }

            if (empty($tag->destination_url) && ! empty($tag->target_url)) {
                $tag->destination_url = $tag->target_url;
            } elseif (empty($tag->target_url) && ! empty($tag->destination_url)) {
                $tag->target_url = $tag->destination_url;
            }

            if ($tag->scans_count > 0 && empty($tag->tap_count)) {
                $tag->tap_count = $tag->scans_count;
            } elseif ($tag->tap_count > 0 && empty($tag->scans_count)) {
                $tag->scans_count = $tag->tap_count;
            }
        });
    }

    protected function label(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?: ($this->name ?: 'NFC Tag #'.$this->id),
            set: fn ($value) => [
                'label' => $value,
                'name' => $value,
            ]
        );
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
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

    /**
     * Polymorphic relation to ScanLog
     */
    public function scanLogs(): MorphMany
    {
        return $this->morphMany(ScanLog::class, 'trackable');
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
     * Helper to log a scan event and increment scan counts
     */
    public function recordScan(?string $ipAddress = null, ?string $userAgent = null): ScanLog
    {
        $this->increment('scans_count');
        $this->update(['last_tapped_at' => now(), 'tap_count' => $this->scans_count]);

        return $this->scanLogs()->create([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'scanned_at' => now(),
        ]);
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
