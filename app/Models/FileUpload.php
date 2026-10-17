<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'uploaded_by_type',
        'uploaded_by_id',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }
}
