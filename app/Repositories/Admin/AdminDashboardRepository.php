<?php

namespace App\Repositories\Admin;

use App\Models\Advertisement;
use App\Models\AndroidApp;
use App\Models\ApiLog;
use App\Models\AppInstallEvent;
use App\Models\AppInstallation;
use App\Models\PushNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AdminDashboardRepository
{
    public function cards(array $filters = []): array
    {
        $installEvents = $this->installEvents($filters);
        $installations = $this->installations($filters);
        $notifications = PushNotification::query()->when($filters['app_id'] ?? null, fn (Builder $query, int $appId) => $query->where('app_id', $appId));

        return [
            'total_apps' => AndroidApp::query()->count(),
            'total_installations' => (clone $installEvents)->count(),
            'today_installations' => (clone $installEvents)->whereDate('created_at', today())->count(),
            'daily_active_users' => (clone $installations)->whereDate('last_active_at', today())->distinct('device_id')->count('device_id'),
            'monthly_active_users' => (clone $installations)->where('last_active_at', '>=', now()->subDays(30))->distinct('device_id')->count('device_id'),
            'total_notifications' => (clone $notifications)->count(),
            'active_advertisements' => Advertisement::query()->where('status', 'active')->when($filters['app_id'] ?? null, fn (Builder $query, int $appId) => $query->where('app_id', $appId))->count(),
        ];
    }

    public function installTrend(array $filters = []): Collection
    {
        return $this->installEvents($filters)
            ->selectRaw('date(created_at) as label, count(*) as total')
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

    public function recentActivities(): Collection
    {
        return ApiLog::query()->latest()->limit(8)->get();
    }

    private function installations(array $filters = []): Builder
    {
        return AppInstallation::query()
            ->when($filters['app_id'] ?? null, fn (Builder $query, int $appId) => $query->where('app_id', $appId))
            ->when($filters['from'] ?? null, fn (Builder $query, string $from) => $query->whereDate('created_at', '>=', Carbon::parse($from)))
            ->when($filters['to'] ?? null, fn (Builder $query, string $to) => $query->whereDate('created_at', '<=', Carbon::parse($to)));
    }

    private function installEvents(array $filters = []): Builder
    {
        return AppInstallEvent::query()
            ->when($filters['app_id'] ?? null, fn (Builder $query, int $appId) => $query->where('app_id', $appId))
            ->when($filters['from'] ?? null, fn (Builder $query, string $from) => $query->whereDate('created_at', '>=', Carbon::parse($from)))
            ->when($filters['to'] ?? null, fn (Builder $query, string $to) => $query->whereDate('created_at', '<=', Carbon::parse($to)));
    }
}
