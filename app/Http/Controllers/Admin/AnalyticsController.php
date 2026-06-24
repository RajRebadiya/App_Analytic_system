<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AndroidApp;
use App\Models\AppInstallEvent;
use App\Models\AppEvent;
use App\Models\AppInstallation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function installations(Request $request): View
    {
        $query = $this->installQuery($request);
        $eventQuery = $this->installEventQuery($request);

        return view('admin.analytics.installations', [
            'apps' => AndroidApp::query()->orderBy('name')->get(),
            'installations' => (clone $query)->with('app')->latest()->paginate($request->integer('per_page', 10))->withQueryString(),
            'daily' => (clone $eventQuery)->selectRaw('date(created_at) as label, count(*) as total')->groupBy('label')->orderBy('label')->get(),
            'devices' => (clone $eventQuery)->select('device_brand', DB::raw('count(*) as total'))->groupBy('device_brand')->orderByDesc('total')->limit(10)->get(),
            'androidVersions' => (clone $eventQuery)->select('android_version', DB::raw('count(*) as total'))->groupBy('android_version')->orderByDesc('total')->limit(10)->get(),
            'appVersions' => (clone $eventQuery)->select('app_version', DB::raw('count(*) as total'))->groupBy('app_version')->orderByDesc('total')->limit(10)->get(),
            'countries' => (clone $query)->select('country_code', DB::raw('count(*) as total'))->whereNotNull('country_code')->where('country_code', '!=', '')->groupBy('country_code')->orderByDesc('total')->get(),
        ]);
    }

    public function activeUsers(Request $request): View
    {
        $query = $this->installQuery($request)->whereNotNull('app_installations.last_active_at');
        $byApp = (clone $query)
            ->join('apps', 'apps.id', '=', 'app_installations.app_id')
            ->select('app_installations.app_id', 'apps.name', DB::raw('count(distinct app_installations.device_id) as total'), DB::raw('max(app_installations.last_active_at) as last_active_at'))
            ->groupBy('app_installations.app_id', 'apps.name')
            ->orderByDesc('total')
            ->get();
        $installCounts = AppInstallEvent::query()
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_id', $appId))
            ->when($request->from, fn ($query, string $from) => $query->whereDate('created_at', '>=', $from))
            ->when($request->to, fn ($query, string $to) => $query->whereDate('created_at', '<=', $to))
            ->select('app_id', DB::raw('count(*) as total'))
            ->groupBy('app_id')
            ->pluck('total', 'app_id');

        return view('admin.analytics.active-users', [
            'apps' => AndroidApp::query()->orderBy('name')->get(),
            'daily' => (clone $query)->selectRaw('date(app_installations.last_active_at) as label, count(distinct app_installations.device_id) as total')->groupBy('label')->orderBy('label')->get(),
            'byApp' => $byApp->map(function ($row) use ($installCounts) {
                $row->install_count = (int) ($installCounts[$row->app_id] ?? 0);

                return $row;
            }),
        ]);
    }

    public function events(Request $request): View
    {
        $query = AppEvent::query()
            ->with('app')
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_id', $appId))
            ->when($request->event_name, fn ($query, string $event) => $query->where('event_name', $event));

        return view('admin.analytics.events', [
            'apps' => AndroidApp::query()->orderBy('name')->get(),
            'events' => (clone $query)->latest()->paginate($request->integer('per_page', 10))->withQueryString(),
            'breakdown' => (clone $query)->select('event_name', DB::raw('count(*) as total'))->groupBy('event_name')->orderByDesc('total')->get(),
        ]);
    }

    public function exportInstallations(Request $request): StreamedResponse
    {
        return response()->streamDownload(function () use ($request): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['App', 'Device ID', 'Country', 'Brand', 'Android', 'Version', 'Installed', 'Last Active']);
            $this->installQuery($request)->with('app')->each(fn (AppInstallation $row) => fputcsv($handle, [$row->app?->name, $row->device_id, $row->country_code, $row->device_brand, $row->android_version, $row->app_version, $row->installed_at, $row->last_active_at]));
            fclose($handle);
        }, 'installations.csv');
    }

    private function installQuery(Request $request)
    {
        return AppInstallation::query()
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_installations.app_id', $appId))
            ->when($request->country_code, fn ($query, string $countryCode) => $query->where('app_installations.country_code', strtoupper(trim($countryCode))))
            ->when($request->from, fn ($query, string $from) => $query->whereDate('app_installations.created_at', '>=', $from))
            ->when($request->to, fn ($query, string $to) => $query->whereDate('app_installations.created_at', '<=', $to));
    }

    private function installEventQuery(Request $request)
    {
        return AppInstallEvent::query()
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_id', $appId))
            ->when($request->country_code, fn ($query, string $countryCode) => $query->where('country_code', strtoupper(trim($countryCode))))
            ->when($request->from, fn ($query, string $from) => $query->whereDate('created_at', '>=', $from))
            ->when($request->to, fn ($query, string $to) => $query->whereDate('created_at', '<=', $to));
    }
}
