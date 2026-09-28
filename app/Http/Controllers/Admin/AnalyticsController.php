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
        $eventQuery = $this->installEventQuery($request);

        return view('admin.analytics.installations', [
            'apps' => AndroidApp::query()->orderBy('name')->get(),
            'installations' => (clone $eventQuery)->with('app')->latest()->paginate($request->integer('per_page', 10))->withQueryString(),
            'daily' => (clone $eventQuery)->selectRaw('date(created_at) as label, count(*) as total')->groupBy('label')->orderBy('label')->get(),
            'devices' => (clone $eventQuery)->select('device_brand', DB::raw('count(*) as total'))->groupBy('device_brand')->orderByDesc('total')->limit(10)->get(),
            'androidVersions' => (clone $eventQuery)->select('android_version', DB::raw('count(*) as total'))->groupBy('android_version')->orderByDesc('total')->limit(10)->get(),
            'appVersions' => (clone $eventQuery)->select('app_version', DB::raw('count(*) as total'))->groupBy('app_version')->orderByDesc('total')->limit(10)->get(),
            'countries' => (clone $eventQuery)->select('country_code', DB::raw('count(*) as total'))->whereNotNull('country_code')->where('country_code', '!=', '')->groupBy('country_code')->orderByDesc('total')->get(),
        ]);
    }

    public function activeUsers(Request $request): View
    {
        $appId = $request->app_id;
        $from = $request->from;
        $to = $request->to;
        $startHour = $request->start_hour;
        $endHour = $request->end_hour;
        $countryCode = $request->country_code ? strtoupper(trim($request->country_code)) : null;

        // Base query for active logs using UNION of app_install_events (historical installs) and app_events (active logs)
        $installEventsQuery = DB::table('app_install_events')
            ->select('app_id', 'device_id', 'created_at')
            ->when($appId, fn ($query) => $query->where('app_id', $appId))
            ->when($countryCode, fn ($query) => $query->where('country_code', $countryCode))
            ->when($from, fn ($query) => $query->whereDate('created_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('created_at', '<=', $to))
            ->when($startHour !== null && $startHour !== '', fn ($query) => $query->whereRaw('HOUR(created_at) >= ?', [(int) $startHour]))
            ->when($endHour !== null && $endHour !== '', fn ($query) => $query->whereRaw('HOUR(created_at) <= ?', [(int) $endHour]));

        $activeEventsQuery = DB::table('app_events')
            ->select('app_id', 'device_id', 'created_at')
            ->where('event_name', 'active')
            ->when($appId, fn ($query) => $query->where('app_id', $appId))
            ->when($from, fn ($query) => $query->whereDate('created_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('created_at', '<=', $to))
            ->when($startHour !== null && $startHour !== '', fn ($query) => $query->whereRaw('HOUR(created_at) >= ?', [(int) $startHour]))
            ->when($endHour !== null && $endHour !== '', fn ($query) => $query->whereRaw('HOUR(created_at) <= ?', [(int) $endHour]));

        $combinedQuery = $installEventsQuery->unionAll($activeEventsQuery);

        $daily = DB::query()
            ->fromSub($combinedQuery, 'active_logs')
            ->selectRaw('DATE(created_at) as label, COUNT(DISTINCT device_id) as total')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $byAppQuery = DB::query()
            ->fromSub($combinedQuery, 'active_logs')
            ->join('apps', 'apps.id', '=', 'active_logs.app_id')
            ->select('active_logs.app_id', 'apps.name', DB::raw('COUNT(DISTINCT active_logs.device_id) as total'), DB::raw('MAX(active_logs.created_at) as last_active_at'))
            ->groupBy('active_logs.app_id', 'apps.name')
            ->orderByDesc('total')
            ->get();

        $installCounts = AppInstallEvent::query()
            ->when($appId, fn ($query) => $query->where('app_id', $appId))
            ->when($from, fn ($query) => $query->whereDate('created_at', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('created_at', '<=', $to))
            ->when($startHour !== null && $startHour !== '', fn ($query) => $query->whereRaw('HOUR(created_at) >= ?', [(int) $startHour]))
            ->when($endHour !== null && $endHour !== '', fn ($query) => $query->whereRaw('HOUR(created_at) <= ?', [(int) $endHour]))
            ->select('app_id', DB::raw('count(*) as total'))
            ->groupBy('app_id')
            ->pluck('total', 'app_id');

        return view('admin.analytics.active-users', [
            'apps' => AndroidApp::query()->orderBy('name')->get(),
            'daily' => $daily,
            'byApp' => $byAppQuery->map(function ($row) use ($installCounts) {
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
            fputcsv($handle, ['App', 'Device ID', 'Country', 'Brand', 'Android', 'Version', 'Installed']);
            $this->installEventQuery($request)->with('app')->each(fn (AppInstallEvent $row) => fputcsv($handle, [$row->app?->name, $row->device_id, $row->country_code, $row->device_brand, $row->android_version, $row->app_version, $row->created_at]));
            fclose($handle);
        }, 'installations.csv');
    }

    private function installQuery(Request $request)
    {
        return AppInstallation::query()
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_installations.app_id', $appId))
            ->when($request->country_code, fn ($query, string $countryCode) => $query->where('app_installations.country_code', strtoupper(trim($countryCode))))
            ->when($request->from, fn ($query, string $from) => $query->whereDate('app_installations.created_at', '>=', $from))
            ->when($request->to, fn ($query, string $to) => $query->whereDate('app_installations.created_at', '<=', $to))
            ->when($request->filled('start_hour'), fn ($query) => $query->whereRaw('HOUR(app_installations.created_at) >= ?', [$request->integer('start_hour')]))
            ->when($request->filled('end_hour'), fn ($query) => $query->whereRaw('HOUR(app_installations.created_at) <= ?', [$request->integer('end_hour')]));
    }

    private function installEventQuery(Request $request)
    {
        return AppInstallEvent::query()
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_id', $appId))
            ->when($request->country_code, fn ($query, string $countryCode) => $query->where('country_code', strtoupper(trim($countryCode))))
            ->when($request->from, fn ($query, string $from) => $query->whereDate('created_at', '>=', $from))
            ->when($request->to, fn ($query, string $to) => $query->whereDate('created_at', '<=', $to))
            ->when($request->filled('start_hour'), fn ($query) => $query->whereRaw('HOUR(created_at) >= ?', [$request->integer('start_hour')]))
            ->when($request->filled('end_hour'), fn ($query) => $query->whereRaw('HOUR(created_at) <= ?', [$request->integer('end_hour')]));
    }
}
