<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdNetworkSettingRequest;
use App\Models\AdNetworkSetting;
use App\Models\AndroidApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdNetworkSettingController extends Controller
{
    public function index(Request $request): View
    {
        $settings = AdNetworkSetting::query()
            ->with('app')
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_id', $appId))
            ->latest()
            ->paginate(15)
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
            ]),
            'apps' => AndroidApp::query()->orderBy('name')->get(),
        ]);
    }

    public function store(AdNetworkSettingRequest $request): RedirectResponse
    {
        AdNetworkSetting::query()->updateOrCreate(
            ['app_id' => $request->integer('app_id')],
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
        return $request->validated() + [
            'is_active' => false,
            'ad_show_status' => false,
            'admob_status' => false,
            'dialog_before_ad_show' => false,
            'need_internet' => false,
            'redirect_other_app_status' => false,
            'update_dialog_status' => false,
        ];
    }
}
