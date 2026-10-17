<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'client_id',
        'created_by',
        'paper_size',
        'columns',
        'rows',
        'qr_size_mm',
        'show_access_code',
        'show_public_code',
        'show_logo',
        'custom_text',
        'logo_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'columns' => 'integer',
            'rows' => 'integer',
            'qr_size_mm' => 'integer',
            'show_access_code' => 'boolean',
            'show_public_code' => 'boolean',
            'show_logo' => 'boolean',
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

    public function items(): HasMany
    {
        return $this->hasMany(QrDocumentItem::class)->orderBy('position_order');
    }
}
