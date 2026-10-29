<?php

namespace App\Observers;

use App\Models\DigitalCard;
use Illuminate\Support\Facades\Auth;

class DigitalCardObserver
{
    public function created(DigitalCard $card): void
    {
        if ($card->client_id && $card->client) {
            $cardTitle = $card->title ?: ($card->name ?: 'Digital Business Card');
            $card->client->clientHistories()->create([
                'user_id' => $card->user_id ?? Auth::id(),
                'action_type' => 'digital_card_created',
                'description' => "Card '{$cardTitle}' created (Slug: {$card->slug}).",
                'metadata' => [
                    'digital_card_id' => $card->id,
                    'slug' => $card->slug,
                    'template_id' => $card->template_id,
                ],
                'created_at' => now(),
            ]);
        }
    }

    public function updated(DigitalCard $card): void
    {
        $dirty = $card->getDirty();
        unset($dirty['updated_at'], $dirty['views_count'], $dirty['last_viewed_at']);

        if (! empty($dirty) && $card->client_id && $card->client) {
            $card->client->clientHistories()->create([
                'user_id' => Auth::id() ?? $card->user_id,
                'action_type' => 'digital_card_updated',
                'description' => "Updated Digital Business Card '{$card->title}'.",
                'metadata' => [
                    'digital_card_id' => $card->id,
                    'changed' => array_keys($dirty),
                ],
                'created_at' => now(),
            ]);
        }
    }

    public function deleted(DigitalCard $card): void
    {
        if ($card->client_id && $card->client) {
            $card->client->clientHistories()->create([
                'user_id' => Auth::id() ?? $card->user_id,
                'action_type' => 'digital_card_deleted',
                'description' => "Deleted Digital Business Card '{$card->title}'.",
                'metadata' => [
                    'digital_card_id' => $card->id,
                    'slug' => $card->slug,
                ],
                'created_at' => now(),
            ]);
        }
    }
}
