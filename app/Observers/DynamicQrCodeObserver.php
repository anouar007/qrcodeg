<?php

namespace App\Observers;

use App\Models\DynamicQrCode;
use Illuminate\Support\Facades\Auth;

class DynamicQrCodeObserver
{
    public function created(DynamicQrCode $qrCode): void
    {
        if ($qrCode->client_id && $qrCode->client) {
            $qrCode->client->clientHistories()->create([
                'user_id' => Auth::id(),
                'action_type' => 'dynamic_qr_created',
                'description' => "Generated Dynamic QR Code [{$qrCode->code}] '{$qrCode->label}' with template '{$qrCode->template_type}'.",
                'metadata' => [
                    'qr_code_id' => $qrCode->id,
                    'code' => $qrCode->code,
                    'target_url' => $qrCode->target_url,
                    'template_type' => $qrCode->template_type,
                ],
                'created_at' => now(),
            ]);
        }
    }

    public function updated(DynamicQrCode $qrCode): void
    {
        $dirty = $qrCode->getDirty();
        unset($dirty['updated_at'], $dirty['scans_count']);

        if (! empty($dirty) && $qrCode->client_id && $qrCode->client) {
            if (array_key_exists('target_url', $dirty)) {
                $description = "QR {$qrCode->code} redirected to URL {$qrCode->target_url}";
            } else {
                $description = "Updated Dynamic QR Code [{$qrCode->code}] '{$qrCode->label}' (".implode(', ', array_keys($dirty)).').';
            }

            $qrCode->client->clientHistories()->create([
                'user_id' => Auth::id(),
                'action_type' => 'dynamic_qr_updated',
                'description' => $description,
                'metadata' => [
                    'qr_code_id' => $qrCode->id,
                    'code' => $qrCode->code,
                    'changed' => array_keys($dirty),
                    'target_url' => $qrCode->target_url,
                ],
                'created_at' => now(),
            ]);
        }
    }

    public function deleted(DynamicQrCode $qrCode): void
    {
        if ($qrCode->client_id && $qrCode->client) {
            $qrCode->client->clientHistories()->create([
                'user_id' => Auth::id(),
                'action_type' => 'dynamic_qr_deleted',
                'description' => "Deleted Dynamic QR Code [{$qrCode->code}] '{$qrCode->label}'.",
                'metadata' => [
                    'qr_code_id' => $qrCode->id,
                    'code' => $qrCode->code,
                ],
                'created_at' => now(),
            ]);
        }
    }
}
