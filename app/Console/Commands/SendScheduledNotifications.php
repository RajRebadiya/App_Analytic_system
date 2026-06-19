<?php

namespace App\Console\Commands;

use App\Models\PushNotification;
use App\Services\Admin\AdminNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled push notifications whose time has arrived';

    /**
     * Execute the console command.
     */
    public function handle(AdminNotificationService $notificationService)
    {
        $notifications = PushNotification::query()
            ->where('status', 'pending')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($notifications->isEmpty()) {
            return Command::SUCCESS;
        }

        $this->info("Found {$notifications->count()} scheduled notifications to send.");

        foreach ($notifications as $notification) {
            try {
                $notificationService->send($notification);
                $this->info("Sent notification ID: {$notification->id}");
            } catch (\Exception $e) {
                $this->error("Failed to send notification ID: {$notification->id}. Error: {$e->getMessage()}");
                Log::error('Scheduled Notification Send Error', [
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Reschedule if frequency is set to everyday
            if (($notification->schedule_frequency ?? 'once') === 'everyday') {
                try {
                    $nextNotification = $notification->replicate();
                    $nextNotification->scheduled_at = $notification->scheduled_at->copy()->addDay();
                    $nextNotification->status = 'pending';
                    $nextNotification->onesignal_response = null;
                    $nextNotification->total_sent = 0;
                    $nextNotification->total_failed = 0;
                    $nextNotification->save();

                    $this->info("Rescheduled daily notification ID: {$notification->id} as next instance ID: {$nextNotification->id} for {$nextNotification->scheduled_at->toDateTimeString()}");
                } catch (\Exception $e) {
                    $this->error("Failed to reschedule daily notification for ID: {$notification->id}. Error: {$e->getMessage()}");
                    Log::error('Failed to reschedule daily notification', [
                        'notification_id' => $notification->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return Command::SUCCESS;
    }
}
