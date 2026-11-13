<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'loyalty_card_id',
        'client_id',
        'action',
        'stamps_delta',
        'points_delta',
        'notes',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'stamps_delta' => 'integer',
            'points_delta' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function loyaltyCard(): BelongsTo
    {
        return $this->belongsTo(LoyaltyCard::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
