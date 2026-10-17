<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrDocumentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_document_id',
        'qr_code_id',
        'position_order',
        'label',
    ];

    protected function casts(): array
    {
        return [
            'position_order' => 'integer',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(QrDocument::class, 'qr_document_id');
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }
}
