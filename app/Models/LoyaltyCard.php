<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LoyaltyCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'card_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'card_type',
        'nfc_uid',
        'stamps_count',
        'max_stamps',
        'reward_text',
        'total_stamps_earned',
        'rewards_redeemed_count',
        'points_balance',
        'points',
        'status',
        'notes',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'stamps_count' => 'integer',
            'max_stamps' => 'integer',
            'total_stamps_earned' => 'integer',
            'rewards_redeemed_count' => 'integer',
            'points_balance' => 'integer',
            'last_activity_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class)->orderBy('created_at', 'desc');
    }

    public function latestTransaction(): HasOne
    {
        return $this->hasOne(LoyaltyTransaction::class)->latestOfMany();
    }

    public function getPointsAttribute(): int
    {
        return (int) ($this->attributes['points_balance'] ?? 0);
    }

    public function setPointsAttribute($value): void
    {
        $this->attributes['points_balance'] = (int) $value;
    }

    public function getTierAttribute(): string
    {
        $pts = $this->points;
        if ($pts >= 1000) {
            return 'platinum';
        }
        if ($pts >= 500) {
            return 'gold';
        }
        if ($pts >= 200) {
            return 'silver';
        }

        return 'bronze';
    }

    public function isRewardAvailable(): bool
    {
        return $this->stamps_count >= $this->max_stamps;
    }

    public function progressPercentage(): float
    {
        $max = max(1, (int) $this->max_stamps);

        return min(100.0, round(($this->stamps_count / $max) * 100, 1));
    }

    public function remainingStampsForReward(): int
    {
        return max(0, (int) $this->max_stamps - (int) $this->stamps_count);
    }

    /**
     * Add stamp(s) to this card and log transaction
     */
    public function addStamp(int $count = 1, ?string $notes = null): bool
    {
        if ($count <= 0) {
            return false;
        }

        $this->stamps_count += $count;
        $this->total_stamps_earned += $count;
        $this->last_activity_at = now();
        $this->save();

        $this->transactions()->create([
            'client_id' => $this->client_id,
            'action' => 'stamp_added',
            'stamps_delta' => $count,
            'points_delta' => 0,
            'notes' => $notes ?: "+{$count} tampon(s) attribué(s)",
            'created_at' => now(),
        ]);

        return true;
    }

    /**
     * Redeem the available reward and deduct max_stamps
     */
    public function redeemReward(?string $notes = null): bool
    {
        if (! $this->isRewardAvailable()) {
            return false;
        }

        $this->stamps_count -= $this->max_stamps;
        $this->rewards_redeemed_count += 1;
        $this->last_activity_at = now();
        $this->save();

        $this->transactions()->create([
            'client_id' => $this->client_id,
            'action' => 'reward_redeemed',
            'stamps_delta' => -$this->max_stamps,
            'points_delta' => 0,
            'notes' => $notes ?: "Récompense réclamée : {$this->reward_text}",
            'created_at' => now(),
        ]);

        return true;
    }

    /**
     * Add points to this card and log transaction
     */
    public function addPoints(int $points, ?string $notes = null): bool
    {
        if ($points <= 0) {
            return false;
        }

        $this->points_balance += $points;
        $this->last_activity_at = now();
        $this->save();

        $this->transactions()->create([
            'client_id' => $this->client_id,
            'action' => 'points_earned',
            'stamps_delta' => 0,
            'points_delta' => $points,
            'notes' => $notes ?: "+{$points} point(s) crédité(s)",
            'created_at' => now(),
        ]);

        return true;
    }

    /**
     * Deduct / use points from this card and log transaction
     */
    public function deductPoints(int $points, ?string $notes = null): bool
    {
        if ($points <= 0 || $this->points_balance < $points) {
            return false;
        }

        $this->points_balance -= $points;
        $this->last_activity_at = now();
        $this->save();

        $this->transactions()->create([
            'client_id' => $this->client_id,
            'action' => 'points_redeemed',
            'stamps_delta' => 0,
            'points_delta' => -$points,
            'notes' => $notes ?: "-{$points} point(s) utilisé(s)",
            'created_at' => now(),
        ]);

        return true;
    }

    public function getPublicUrlAttribute(): string
    {
        return route('loyalty.public.show', ['card_code' => $this->card_code]);
    }

    public static function generateUniqueCardCode(): string
    {
        do {
            $part1 = strtoupper(Str::random(4));
            $part2 = strtoupper(Str::random(4));
            $code = "LC-{$part1}-{$part2}";
        } while (self::where('card_code', $code)->exists());

        return $code;
    }
}
