<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitalCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'created_by',
        'slug',
        'name',
        'template',
        'profile_image',
        'cover_image',
        'full_name',
        'job_title',
        'company',
        'bio',
        'badge_text',
        'links',
        'layout_settings',
        'style_settings',
        'status',
        'views_count',
        'last_viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'links' => 'array',
            'layout_settings' => 'array',
            'style_settings' => 'array',
            'views_count' => 'integer',
            'last_viewed_at' => 'datetime',
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

    public function nfcTags(): HasMany
    {
        return $this->hasMany(NfcTag::class);
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
        return url('/c/'.$this->slug);
    }

    public static function generateUniqueSlug(): string
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charLength = strlen($characters);

        do {
            $randomString = '';
            $bytes = random_bytes(6);
            for ($i = 0; $i < 6; $i++) {
                $randomString .= $characters[ord($bytes[$i]) % $charLength];
            }
            $slug = 'CD-'.$randomString;
        } while (self::where('slug', $slug)->exists());

        return $slug;
    }

    /**
     * Generate standard vCard (.vcf) format string
     */
    public function generateVCard(): string
    {
        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= 'FN;CHARSET=UTF-8:'.($this->full_name ?: $this->name)."\r\n";

        if ($this->company) {
            $vcard .= 'ORG;CHARSET=UTF-8:'.$this->company."\r\n";
        }
        if ($this->job_title) {
            $vcard .= 'TITLE;CHARSET=UTF-8:'.$this->job_title."\r\n";
        }
        if ($this->bio) {
            $vcard .= 'NOTE;CHARSET=UTF-8:'.str_replace(["\r", "\n"], ' ', $this->bio)."\r\n";
        }

        if (is_array($this->links)) {
            foreach ($this->links as $link) {
                $type = $link['type'] ?? '';
                $val = trim($link['value'] ?? '');
                if (! $val) {
                    continue;
                }

                if ($type === 'phone') {
                    $vcard .= 'TEL;TYPE=CELL:'.$val."\r\n";
                } elseif ($type === 'whatsapp') {
                    $vcard .= 'TEL;TYPE=WHATSAPP:'.$val."\r\n";
                } elseif ($type === 'email') {
                    $vcard .= 'EMAIL;TYPE=INTERNET:'.$val."\r\n";
                } elseif ($type === 'website') {
                    $vcard .= 'URL:'.$val."\r\n";
                }
            }
        }

        $vcard .= 'URL:'.$this->public_url."\r\n";
        $vcard .= "END:VCARD\r\n";

        return $vcard;
    }
}
