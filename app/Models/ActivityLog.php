<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_id',
        'action',
        'resource_type',
        'resource_id',
        'ip_address',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public static function log(
        string $action,
        ?string $resourceType = null,
        ?int $resourceId = null,
        ?array $details = null,
        ?User $user = null,
        ?Client $client = null
    ): self {
        $user = $user ?? auth('web')->user();
        $client = $client ?? auth('client')->user();

        return self::create([
            'user_id' => $user?->id,
            'client_id' => $client?->id,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'ip_address' => request()->ip(),
            'details' => $details,
        ]);
    }
}
