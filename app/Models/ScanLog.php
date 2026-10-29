<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ScanLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'trackable_type',
        'trackable_id',
        'ip_address',
        'user_agent',
        'scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ScanLog $log) {
            if (empty($log->scanned_at)) {
                $log->scanned_at = now();
            }
        });
    }

    /**
     * Polymorphic relation to either NfcTag or DynamicQrCode
     */
    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }
}
