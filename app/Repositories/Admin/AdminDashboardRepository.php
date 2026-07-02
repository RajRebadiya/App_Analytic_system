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
        $appId = $filters['app_id'] ?? null;
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        // Base queries
        $installationsQuery = AppInstallation::query()
            ->when($appId, fn ($query) => $query->where('app_id', $appId));

        $installEventsQuery = AppInstallEvent::query()
            ->when($appId, fn ($query) => $query->where('app_id', $appId));

        // Filtered query for date-range specific stats
        $filteredInstallEvents = (clone $installEventsQuery)
            ->when($from, fn ($query) => $query->whereDate('created_at', '>=', Carbon::parse($from)))
            ->when($to, fn ($query) => $query->whereDate('created_at', '<=', Carbon::parse($to)));

        return [
            'total_apps' => AndroidApp::query()->count(),
            'total_installations' => $filteredInstallEvents->count(),
            'today_installations' => (clone $installEventsQuery)->whereDate('created_at', today())->count(),
            'daily_active_users' => (clone $installationsQuery)->whereDate('last_active_at', today())->count(),
            'monthly_active_users' => (clone $installationsQuery)->where('last_active_at', '>=', now()->subDays(30))->count(),
            'total_notifications' => PushNotification::query()->when($appId, fn ($query) => $query->where('app_id', $appId))->count(),
            'active_advertisements' => Advertisement::query()->where('status', 'active')->when($appId, fn ($query) => $query->where('app_id', $appId))->count(),
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
        $appId = $filters['app_id'] ?? null;
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        return AppInstallation::query()
            ->whereNotNull('last_active_at')
            ->when($appId, fn ($query) => $query->where('app_id', $appId))
            ->when($from, fn ($query) => $query->whereDate('last_active_at', '>=', Carbon::parse($from)))
            ->when($to, fn ($query) => $query->whereDate('last_active_at', '<=', Carbon::parse($to)))
            ->selectRaw('date(last_active_at) as label, count(distinct device_id) as total')
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
