<?php

namespace App\Jobs;

use App\Models\NotificationLog;
use App\Models\PushNotification;
use App\Services\FirebaseCloudMessagingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPushNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(public int $notificationId, public array $tokens) {}

    public function handle(FirebaseCloudMessagingService $fcm): void
    {
        $notification = PushNotification::query()->findOrFail($this->notificationId);
        $sent = 0;
        $failed = 0;

        foreach ($this->tokens as $token) {
            $result = $fcm->send($token['fcm_token'], [
                'title' => $notification->title,
                'description' => $notification->description,
                'image' => $notification->image,
                'data' => [
                    'notification_id' => $notification->id,
                    'type' => $notification->notification_type,
                    'redirect_screen' => $notification->redirect_screen,
                    'redirect_data' => json_encode($notification->redirect_data ?? []),
                ],
            ]);

            $status = $result['successful'] ? 'sent' : 'failed';
            $sent += $result['successful'] ? 1 : 0;
            $failed += $result['successful'] ? 0 : 1;

            NotificationLog::query()->create([
                'notification_id' => $notification->id,
                'device_id' => $token['device_id'],
                'fcm_token' => $token['fcm_token'],
                'status' => $status,
                'response' => $result['response'],
                'sent_at' => now(),
            ]);
        }

        $notification->increment('total_sent', $sent);
        $notification->increment('total_failed', $failed);
    }
}
