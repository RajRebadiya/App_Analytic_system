<?php

namespace App\Services;

use App\Models\AndroidApp;
use App\Models\AppEvent;
use App\Models\AppInstallation;
use App\Models\NotificationLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function dashboard(?int $appId = null): array
    {
        $installations = AppInstallation::query()->when($appId, fn ($query) => $query->where('app_id', $appId));
        $events = AppEvent::query()->when($appId, fn ($query) => $query->where('app_id', $appId));

        return [
            'apps' => AndroidApp::query()->count(),
            'total_installs' => (clone $installations)->count(),
            'daily_installs' => (clone $installations)->whereDate('installed_at', today())->count(),
            'daily_active_users' => (clone $installations)->whereDate('last_active_at', today())->distinct('device_id')->count('device_id'),
            'monthly_active_users' => (clone $installations)->where('last_active_at', '>=', Carbon::now()->subDays(30))->distinct('device_id')->count('device_id'),
            'ad_clicks' => (clone $events)->where('event_name', 'ad_click')->count(),
            'events_by_name' => (clone $events)->select('event_name', DB::raw('count(*) as total'))->groupBy('event_name')->pluck('total', 'event_name'),
            'notification_success_rate' => $this->notificationSuccessRate(),
        ];
    }

    public function notificationSuccessRate(): float
    {
        $total = NotificationLog::query()->count();

        if ($total === 0) {
            return 0.0;
        }

        $sent = NotificationLog::query()->where('status', 'sent')->count();

        return round(($sent / $total) * 100, 2);
    }
}
