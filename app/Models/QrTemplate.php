<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'preview_image',
        'default_colors',
        'layout_config',
        'content_preset',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_colors' => 'array',
            'layout_config' => 'array',
            'content_preset' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
