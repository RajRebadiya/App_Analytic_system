<?php

namespace App\Services\Admin;

use App\Models\PushNotification;
use App\Services\OneSignalNotificationService;
use Illuminate\Support\Facades\Log;

class AdminNotificationService
{
    public function __construct(private readonly OneSignalNotificationService $oneSignal) {}

    public function send(PushNotification $notification): array
    {
        if (! $notification->is_active) {
            return [
                'successful' => false,
                'status_code' => null,
                'response' => ['error' => 'Cannot send an inactive notification.'],
            ];
        }

        try {
            $app = $notification->app;
            if (! $app?->onesignal_app_id || ! $app?->onesignal_api_key) {
                throw new \RuntimeException('OneSignal credentials are missing for the selected app. Please set them in the app edit screen.');
            }

            $result = $this->oneSignal->sendToAll(
                $notification->title,
                (string) $notification->description,
                $app?->onesignal_app_id,
                $app?->onesignal_api_key,
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

        $image = str_replace('\\', '/', $image);
        $image = ltrim($image, '/');
        $image = preg_replace('#^(storage/|public/)#', '', $image) ?? $image;
        $image = preg_replace('#^notifications/#', 'notification/', $image) ?? $image;

        return url($image);
    }
}
