<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_id',
        'foreground_color',
        'background_color',
        'dot_style',
        'corner_style',
        'eye_style',
        'logo_path',
        'logo_size',
        'logo_padding',
        'frame_style',
        'frame_text',
        'frame_color',
        'error_correction',
        'qr_size',
    ];

    protected function casts(): array
    {
        return [
            'logo_size' => 'integer',
            'logo_padding' => 'integer',
            'qr_size' => 'integer',
        ];
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }
}
