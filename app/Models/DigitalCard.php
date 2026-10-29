<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'user_id',
        'title',
        'slug',
        'template_id',
        'theme_config',
        'content_blocks',
        'views_count',
        'status',
        // Legacy & extended attributes
        'created_by',
        'name',
        'template',
        'profile_image',
        'cover_image',
        'profile_photo_path',
        'cover_photo_path',
        'full_name',
        'job_title',
        'company',
        'bio',
        'badge_text',
        'links',
        'layout_settings',
        'style_settings',
        'last_viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'theme_config' => 'array',
            'content_blocks' => 'array',
            'links' => 'array',
            'layout_settings' => 'array',
            'style_settings' => 'array',
            'views_count' => 'integer',
            'last_viewed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DigitalCard $card) {
            if (empty($card->slug)) {
                $card->slug = static::generateUniqueSlug();
            }
            if (empty($card->title)) {
                $card->title = $card->name ?: ($card->full_name ?: 'Digital Business Card');
            }
            if (empty($card->name)) {
                $card->name = $card->title;
            }
            if (empty($card->template_id)) {
                $card->template_id = $card->template ?: 'modern';
            }
            if (empty($card->user_id) && ! empty($card->created_by)) {
                $card->user_id = $card->created_by;
            }
            if (empty($card->created_by) && ! empty($card->user_id)) {
                $card->created_by = $card->user_id;
            }
        });

        static::saving(function (DigitalCard $card) {
            // Keep style_settings <-> theme_config in sync
            if (! empty($card->theme_config) && empty($card->style_settings)) {
                $card->style_settings = $card->theme_config;
            } elseif (! empty($card->style_settings) && empty($card->theme_config)) {
                $card->theme_config = $card->style_settings;
            }

            // Keep content_blocks <-> links in sync
            if (! empty($card->content_blocks) && empty($card->links)) {
                $card->links = $card->content_blocks['social_links'] ?? ($card->content_blocks['links'] ?? null);
            }

            // Sync photo paths
            if (! empty($card->profile_photo_path) && empty($card->profile_image)) {
                $card->profile_image = $card->profile_photo_path;
            } elseif (! empty($card->profile_image) && empty($card->profile_photo_path)) {
                $card->profile_photo_path = $card->profile_image;
            }

            if (! empty($card->cover_photo_path) && empty($card->cover_image)) {
                $card->cover_image = $card->cover_photo_path;
            } elseif (! empty($card->cover_image) && empty($card->cover_photo_path)) {
                $card->cover_photo_path = $card->cover_image;
            }
        });
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?: ($this->name ?: ($this->full_name ?: 'Card #'.$this->id)),
            set: fn ($value) => [
                'title' => $value,
                'name' => $value,
            ]
        );
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->user();
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
        $vcard .= 'FN;CHARSET=UTF-8:'.($this->full_name ?: ($this->title ?: $this->name))."\r\n";

        if ($this->company) {
            $vcard .= 'ORG;CHARSET=UTF-8:'.$this->company."\r\n";
        }
        if ($this->job_title) {
            $vcard .= 'TITLE;CHARSET=UTF-8:'.$this->job_title."\r\n";
        }
        if ($this->bio) {
            $vcard .= 'NOTE;CHARSET=UTF-8:'.str_replace(["\r", "\n"], ' ', $this->bio)."\r\n";
        }

        $items = $this->content_blocks['contact_details'] ?? ($this->content_blocks ?? ($this->links ?? []));
        if (is_array($items)) {
            foreach ($items as $link) {
                $type = $link['type'] ?? '';
                $val = trim($link['url'] ?? ($link['value'] ?? ''));
                if (! $val) {
                    continue;
                }

                if ($type === 'phone' || $type === 'contact') {
                    $vcard .= 'TEL;TYPE=CELL:'.$val."\r\n";
                } elseif ($type === 'whatsapp') {
                    $vcard .= 'TEL;TYPE=WHATSAPP:'.$val."\r\n";
                } elseif ($type === 'email') {
                    $vcard .= 'EMAIL;TYPE=INTERNET:'.$val."\r\n";
                } elseif ($type === 'website' || $type === 'custom_link') {
                    $vcard .= 'URL:'.$val."\r\n";
                }
            }
        }

        $vcard .= 'URL:'.$this->public_url."\r\n";
        $vcard .= "END:VCARD\r\n";

        return $vcard;
    }
}
