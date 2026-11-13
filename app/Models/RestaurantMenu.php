<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RestaurantMenu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'title',
        'slug',
        'brand_name',
        'brand_type',
        'currency',
        'description',
        'logo_url',
        'cover_image',
        'theme_color',
        'bg_color',
        'item_bg_color',
        'item_text_color',
        'service_bg_color',
        'service_text_color',
        'categories',
        'nav_style',
        'table_service_enabled',
        'phone',
        'whatsapp',
        'whatsapp_message',
        'instagram',
        'website',
        'wifi_ssid',
        'wifi_password',
        'address',
        'views_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'categories' => 'array',
            'views_count' => 'integer',
            'table_service_enabled' => 'boolean',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getPublicUrlAttribute(): string
    {
        return route('menu.public.show', $this->slug);
    }

    public function getTotalCategoriesCountAttribute(): int
    {
        return is_array($this->categories) ? count($this->categories) : 0;
    }

    public function getTotalItemsCountAttribute(): int
    {
        if (! is_array($this->categories)) {
            return 0;
        }

        $count = 0;
        foreach ($this->categories as $category) {
            if (isset($category['items']) && is_array($category['items'])) {
                $count += count($category['items']);
            }
        }

        return $count;
    }

    public function recordView(): void
    {
        $this->increment('views_count');
    }

    /**
     * Generate unique slug from title
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        if (empty($base)) {
            $base = 'menu';
        }

        $slug = $base;
        $counter = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
