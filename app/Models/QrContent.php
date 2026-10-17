<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_id',
        'type',
        'title',
        'content_data',
    ];

    protected function casts(): array
    {
        return [
            'content_data' => 'array',
        ];
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }
}
