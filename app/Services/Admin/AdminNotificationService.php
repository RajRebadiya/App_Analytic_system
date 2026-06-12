<?php

namespace App\Services\Admin;

use App\Models\PushNotification;
use App\Services\OneSignalNotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminNotificationService
{
    public function __construct(private readonly OneSignalNotificationService $oneSignal) {}

    public function send(PushNotification $notification): array
    {
        try {
            $result = $this->oneSignal->sendToAll(
                $notification->title,
                (string) $notification->description,
                $this->publicImageUrl($notification->image),
            );
            $recipients = (int) data_get($result, 'response.recipients', 0);

            $notification->update([
                'onesignal_response' => $result,
                'status' => $result['successful'] ? 'success' : 'failed',
                'total_sent' => $result['successful'] ? $recipients : 0,
                'total_failed' => $result['successful'] ? 0 : 1,
            ]);

            return $result;
        } catch (\Throwable $exception) {
            Log::error('OneSignal notification send failed.', [
                'notification_id' => $notification->id,
                'error' => $exception->getMessage(),
            ]);

            $result = [
                'successful' => false,
                'status_code' => null,
                'response' => ['error' => $exception->getMessage()],
            ];

            $notification->update([
                'onesignal_response' => $result,
                'status' => 'failed',
                'total_failed' => 1,
            ]);

            return $result;
        }
    }

    private function publicImageUrl(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        return url(Storage::disk('public')->url($image));
    }
}
