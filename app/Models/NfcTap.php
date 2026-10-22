<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NfcTap extends Model
{
    use HasFactory;

    protected $fillable = [
        'nfc_tag_id',
        'tapped_at',
        'ip_hash',
        'device_type',
        'operating_system',
        'browser',
        'referrer',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'tapped_at' => 'datetime',
        ];
    }

    public function nfcTag(): BelongsTo
    {
        return $this->belongsTo(NfcTag::class);
    }
}
