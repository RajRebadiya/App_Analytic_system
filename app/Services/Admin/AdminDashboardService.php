<?php

namespace App\Services\Admin;

use App\Models\AndroidApp;
use App\Repositories\Admin\AdminDashboardRepository;

class AdminDashboardService
{
    public function __construct(private readonly AdminDashboardRepository $repository) {}

    public function data(array $filters = []): array
    {
        $installs = $this->repository->installTrend($filters);
        $activity = $this->repository->activityTrend($filters);
        $events = $this->repository->eventBreakdown($filters);

        return [
            'apps' => AndroidApp::query()->orderBy('name')->get(),
            'filters' => $filters,
            'cards' => $this->repository->cards($filters),
            'recentActivities' => $this->repository->recentActivities(),
            'charts' => [
                'installLabels' => $installs->pluck('label'),
                'installData' => $installs->pluck('total'),
                'activityLabels' => $activity->pluck('label'),
                'activityData' => $activity->pluck('total'),
                'eventLabels' => $events->pluck('event_name'),
                'eventData' => $events->pluck('total'),
            ],
        ];
    }
}
