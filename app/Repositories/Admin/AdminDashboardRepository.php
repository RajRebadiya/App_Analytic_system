<?php

namespace App\Repositories\Admin;

use App\Models\Advertisement;
use App\Models\AndroidApp;
use App\Models\ApiLog;
use App\Models\AppEvent;
use App\Models\AppInstallation;
use App\Models\AppVersion;
use App\Models\PushNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminDashboardRepository
{
    public function cards(array $filters = []): array
    {
        $installations = $this->installations($filters);
        $notifications = PushNotification::query()->when($filters['app_id'] ?? null, fn (Builder $query, int $appId) => $query->where('app_id', $appId));

        return [
            'total_apps' => AndroidApp::query()->count(),
            'total_installations' => (clone $installations)->count(),
            'today_installations' => (clone $installations)->whereDate('installed_at', today())->count(),
            'daily_active_users' => (clone $installations)->whereDate('last_active_at', today())->distinct('device_id')->count('device_id'),
            'monthly_active_users' => (clone $installations)->where('last_active_at', '>=', now()->subDays(30))->distinct('device_id')->count('device_id'),
            'total_notifications_sent' => (clone $notifications)->sum('total_sent'),
            'notification_success_rate' => $this->notificationSuccessRate($filters),
            'active_advertisements' => Advertisement::query()->where('status', 'active')->when($filters['app_id'] ?? null, fn (Builder $query, int $appId) => $query->where('app_id', $appId))->count(),
            'running_versions' => AppVersion::query()->when($filters['app_id'] ?? null, fn (Builder $query, int $appId) => $query->where('app_id', $appId))->latest()->limit(5)->get(),
        ];
    }

    public function installTrend(array $filters = []): Collection
    {
        return $this->installations($filters)
            ->selectRaw('date(installed_at) as label, count(*) as total')
            ->groupBy('label')
            ->orderBy('label')
            ->get();
    }

    public function activityTrend(array $filters = []): Collection
    {
        return $this->installations($filters)
            ->selectRaw('date(last_active_at) as label, count(distinct device_id) as total')
            ->whereNotNull('last_active_at')
            ->groupBy('label')
            ->orderBy('label')
            ->get();
    }

    public function eventBreakdown(array $filters = []): Collection
    {
        return AppEvent::query()
            ->when($filters['app_id'] ?? null, fn (Builder $query, int $appId) => $query->where('app_id', $appId))
            ->when($filters['from'] ?? null, fn (Builder $query, string $from) => $query->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn (Builder $query, string $to) => $query->whereDate('created_at', '<=', $to))
            ->select('event_name', DB::raw('count(*) as total'))
            ->groupBy('event_name')
            ->orderByDesc('total')
            ->get();
    }

    public function recentActivities(): Collection
    {
        return ApiLog::query()->latest()->limit(8)->get();
    }

    public function notificationSuccessRate(array $filters = []): float
    {
        $query = DB::table('notification_logs')
            ->join('notifications', 'notifications.id', '=', 'notification_logs.notification_id')
            ->when($filters['app_id'] ?? null, fn ($query, int $appId) => $query->where('notifications.app_id', $appId));

        $total = (clone $query)->count();

        if ($total === 0) {
            return 0.0;
        }

        return round(((clone $query)->where('notification_logs.status', 'sent')->count() / $total) * 100, 2);
    }

    private function installations(array $filters = []): Builder
    {
        return AppInstallation::query()
            ->when($filters['app_id'] ?? null, fn (Builder $query, int $appId) => $query->where('app_id', $appId))
            ->when($filters['from'] ?? null, fn (Builder $query, string $from) => $query->whereDate('created_at', '>=', Carbon::parse($from)))
            ->when($filters['to'] ?? null, fn (Builder $query, string $to) => $query->whereDate('created_at', '<=', Carbon::parse($to)));
    }
}
