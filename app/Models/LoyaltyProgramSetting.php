<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyProgramSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'points_enabled',
        'calculation_mode',
        'currency',
        'spend_amount_ratio',
        'points_per_ratio',
        'min_spend_amount',
        'redemption_points',
        'redemption_value',
        'services_catalog',
    ];

    protected function casts(): array
    {
        return [
            'points_enabled' => 'boolean',
            'spend_amount_ratio' => 'float',
            'points_per_ratio' => 'integer',
            'min_spend_amount' => 'float',
            'redemption_points' => 'integer',
            'redemption_value' => 'float',
            'services_catalog' => 'array',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Calculate points earned based on money spent according to merchant's rule
     */
    public function calculatePointsForSpend(float $amount): int
    {
        if (! $this->points_enabled || $amount < $this->min_spend_amount) {
            return 0;
        }

        if ($this->spend_amount_ratio <= 0) {
            return 0;
        }

        $units = floor($amount / $this->spend_amount_ratio);

        return (int) ($units * $this->points_per_ratio);
    }

    /**
     * Calculate monetary discount equivalent for a given number of redeemed points
     */
    public function calculateDiscountForPoints(int $points): float
    {
        if ($this->redemption_points <= 0 || $points <= 0) {
            return 0.0;
        }

        $rate = $this->redemption_value / $this->redemption_points;

        return round($points * $rate, 2);
    }

    /**
     * Get or create default program settings for a client
     */
    public static function getOrCreateForClient(Client $client): self
    {
        return static::firstOrCreate(
            ['client_id' => $client->id],
            [
                'points_enabled' => true,
                'calculation_mode' => 'spend_amount',
                'currency' => 'DH',
                'spend_amount_ratio' => 10.00,
                'points_per_ratio' => 1,
                'min_spend_amount' => 0.00,
                'redemption_points' => 100,
                'redemption_value' => 10.00,
                'services_catalog' => [
                    ['id' => 1, 'name' => 'Prestation Standard', 'price' => 50, 'points' => 5],
                    ['id' => 2, 'name' => 'Prestation Premium', 'price' => 100, 'points' => 12],
                    ['id' => 3, 'name' => 'Menu VIP / Formule Complète', 'price' => 150, 'points' => 20],
                ],
            ]
        );
    }
}
