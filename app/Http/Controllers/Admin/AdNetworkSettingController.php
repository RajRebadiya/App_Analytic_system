<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdNetworkSettingRequest;
use App\Models\AdNetworkSetting;
use App\Models\AndroidApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdNetworkSettingController extends Controller
{
    public function index(Request $request): View
    {
        $settings = AdNetworkSetting::query()
            ->with('app')
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_id', $appId))
            ->latest()
            ->paginate($request->integer('per_page', 10))
            ->withQueryString();

        return view('admin.ad-settings.index', [
            'settings' => $settings,
            'apps' => AndroidApp::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.ad-settings.form', [
            'setting' => new AdNetworkSetting([
                'is_active' => true,
                'ad_show_status' => true,
                'admob_status' => true,
                'how_show_ad' => 0,
                'ad_platform_sequence' => 'Admob',
                'main_click_count' => 1,
                'inner_click_count' => 1,
                'dialog_time_seconds' => 2,
                'ad_splash' => 'splash_appopen',
                'ad_inter' => 'admob',
                'ad_appopen' => 'appopen',
                'ad_native' => 'admob',
                'ad_small_native' => 'admob',
                'ad_banner' => 'admob',
                'ad_qureka' => 'off',
                'download_status' => 'off',
                'background_status' => 'off',
                'popup_status' => 'off',
                'main_click_status' => 'on',
            ]),
            'apps' => AndroidApp::query()->orderBy('name')->get(),
        ]);
    }

    public function store(AdNetworkSettingRequest $request): RedirectResponse
    {
        AdNetworkSetting::query()->updateOrCreate(
            ['app_id' => $this->appDatabaseId($request)],
            $this->payload($request),
        );

        return redirect()->route('admin.ad-settings.index')->with('status', 'Ad settings saved.');
    }

    public function edit(AdNetworkSetting $adSetting): View
    {
        return view('admin.ad-settings.form', [
            'setting' => $adSetting,
            'apps' => AndroidApp::query()->orderBy('name')->get(),
        ]);
    }

    public function update(AdNetworkSettingRequest $request, AdNetworkSetting $adSetting): RedirectResponse
    {
        $adSetting->update($this->payload($request));

        return redirect()->route('admin.ad-settings.index')->with('status', 'Ad settings updated.');
    }

    public function destroy(AdNetworkSetting $adSetting): RedirectResponse
    {
        $adSetting->delete();

        return back()->with('status', 'Ad settings deleted.');
    }

    private function payload(AdNetworkSettingRequest $request): array
    {
        $payload = AdNetworkSetting::normalizePayload($request->validated() + [
            'is_active' => false,
            'ad_show_status' => false,
            'admob_status' => false,
            'how_show_ad' => 0,
            'main_click_count' => 1,
            'inner_click_count' => 1,
            'dialog_before_ad_show' => false,
            'dialog_time_seconds' => 2,
            'need_internet' => false,
            'redirect_other_app_status' => false,
            'update_dialog_status' => false,
        ]);

        $payload['app_id'] = $this->appDatabaseId($request);

        // Process others: filter out entries with empty keys
        $others = $request->input('others', []);
        $payload['others'] = collect($others)
            ->filter(fn($pair) => !empty($pair['key']))
            ->values()
            ->map(fn($pair) => ['key' => trim($pair['key']), 'value' => $pair['value'] ?? ''])
            ->toArray();

        return $payload;
    }

    private function appDatabaseId(AdNetworkSettingRequest $request): int
    {
        $data = $request->validated();

        if (! empty($data['app_db_id'])) {
            return (int) $data['app_db_id'];
        }

        if (! empty($data['app_package_name']) || ! empty($data['package_name'])) {
            $packageName = $data['app_package_name'] ?? $data['package_name'];
            $app = AndroidApp::query()->where('package_name', $packageName)->first();

            if ($app) {
                return $app->id;
            }
        }

        if (! empty($data['app_id']) && is_numeric($data['app_id'])) {
            return (int) $data['app_id'];
        }

        throw ValidationException::withMessages([
            'app_package_name' => ['App not found. Send app_package_name, package_name, app_db_id, or numeric app_id.'],
        ]);
    }
}
