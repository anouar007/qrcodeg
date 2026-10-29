<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class DynamicQrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'code',
        'label',
        'target_url',
        'template_type',
        'template_image',
        'design_config',
        'scans_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'scans_count' => 'integer',
            'design_config' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DynamicQrCode $qrCode) {
            if (empty($qrCode->code)) {
                $qrCode->code = static::generateUniqueCode();
            }
        });
    }

    /**
     * Get the permanent redirection route for this QR code
     */
    public function getEncodedUrlAttribute(): string
    {
        return route('qr.redirect', ['code' => $this->code]);
    }

    /**
     * Auto-generating code generator using prefix "VP-" + unique alphanumeric or numeric sequence
     * Format: "VP-XXXXXX" (e.g., VP-984210 or VP-8F29A1)
     */
    public static function generateUniqueCode(int $length = 6, bool $digitsOnly = false): string
    {
        $characters = $digitsOnly ? '0123456789' : '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charLength = strlen($characters);

        do {
            $random = '';
            $bytes = random_bytes($length);
            for ($i = 0; $i < $length; $i++) {
                $random .= $characters[ord($bytes[$i]) % $charLength];
            }
            $code = 'VP-'.$random;
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * Generate sequential code with VP- prefix (e.g. VP-000001, VP-000002)
     */
    public static function generateSequentialCode(int $sequenceNumber): string
    {
        $num = max(1, $sequenceNumber);
        do {
            $code = 'VP-'.str_pad((string) $num, 6, '0', STR_PAD_LEFT);
            $num++;
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Polymorphic relation to scan logs
     */
    public function scanLogs(): MorphMany
    {
        return $this->morphMany(ScanLog::class, 'trackable');
    }

    /**
     * Helper to log a scan event and increment scan count
     */
    public function recordScan(?string $ipAddress = null, ?string $userAgent = null): ScanLog
    {
        $this->increment('scans_count');

        return $this->scanLogs()->create([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'scanned_at' => now(),
        ]);
    }
}
