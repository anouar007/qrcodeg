<?php

namespace App\Observers;

use App\Models\NfcTag;
use Illuminate\Support\Facades\Auth;

class NfcTagObserver
{
    public function created(NfcTag $tag): void
    {
        if ($tag->client_id && $tag->client) {
            $tag->client->clientHistories()->create([
                'user_id' => $tag->created_by ?? Auth::id(),
                'action_type' => 'nfc_tag_created',
                'description' => "Assigned new NFC Tag '{$tag->label}' (UID: {$tag->tag_uid}).",
                'metadata' => [
                    'nfc_tag_id' => $tag->id,
                    'tag_uid' => $tag->tag_uid,
                    'destination_url' => $tag->destination_url,
                ],
                'created_at' => now(),
            ]);
        }
    }

    public function updated(NfcTag $tag): void
    {
        $dirty = $tag->getDirty();
        unset($dirty['updated_at'], $dirty['scans_count'], $dirty['tap_count'], $dirty['last_tapped_at']);

        if (! empty($dirty) && $tag->client_id && $tag->client) {
            if (array_key_exists('destination_url', $dirty) || array_key_exists('target_url', $dirty)) {
                $description = "NFC Tag '{$tag->label}' (UID: {$tag->tag_uid}) redirected to URL {$tag->destination_url}";
            } else {
                $description = "Updated NFC Tag '{$tag->label}'.";
            }

            $tag->client->clientHistories()->create([
                'user_id' => Auth::id() ?? $tag->created_by,
                'action_type' => 'nfc_tag_updated',
                'description' => $description,
                'metadata' => [
                    'nfc_tag_id' => $tag->id,
                    'tag_uid' => $tag->tag_uid,
                    'destination_url' => $tag->destination_url,
                    'changed' => array_keys($dirty),
                ],
                'created_at' => now(),
            ]);
        }
    }

    public function deleted(NfcTag $tag): void
    {
        if ($tag->client_id && $tag->client) {
            $tag->client->clientHistories()->create([
                'user_id' => Auth::id() ?? $tag->created_by,
                'action_type' => 'nfc_tag_deleted',
                'description' => "Deleted NFC Tag '{$tag->label}'.",
                'metadata' => [
                    'nfc_tag_id' => $tag->id,
                    'tag_uid' => $tag->tag_uid,
                ],
                'created_at' => now(),
            ]);
        }
    }
}
