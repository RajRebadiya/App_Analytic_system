<?php

namespace App\Services;

use App\Jobs\SendPushNotificationJob;
use App\Models\DeviceToken;
use App\Models\PushNotification;

class NotificationService
{
    public function dispatch(PushNotification $notification): void
    {
        if (! $notification->is_active) {
            return;
        }

        DeviceToken::query()
            ->where('app_id', $notification->app_id)
            ->where('is_active', true)
            ->select(['device_id', 'fcm_token'])
            ->chunkById(500, function ($tokens) use ($notification): void {
                SendPushNotificationJob::dispatch($notification->id, $tokens->toArray())->onQueue('notifications');
            });
    }
}
