<?php

namespace App\Services\Admin;

use App\Models\PushNotification;
use App\Services\NotificationService;

class AdminNotificationService
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function send(PushNotification $notification): void
    {
        $this->notificationService->dispatch($notification);
    }
}
