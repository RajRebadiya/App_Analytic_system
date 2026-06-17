<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'app_id',
    'is_active',
    'ad_show_status',
    'admob_status',
    'admob_app_id',
    'admob_banner_id',
    'admob_medium_rectangle_id',
    'admob_interstitial_id',
    'admob_native_id',
    'admob_rewarded_id',
    'admob_app_open_id',
    'adx_inter_id',
    'adx_banner_id',
    'adx_medium_rectangle_id',
    'adx_native_id',
    'adx_app_open_id',
    'fb_inter_id',
    'fb_banner_id',
    'fb_medium_rectangle_id',
    'fb_native_id',
    'fb_native_banner_id',
    'wortise_app_id',
    'wortise_app_open_id',
    'wortise_inter_id',
    'wortise_banner_id',
    'wortise_medium_rectangle_id',
    'wortise_native_id',
    'how_show_ad',
    'ad_platform_sequence',
    'alternate_ad_show',
    'main_click_count',
    'inner_click_count',
    'ad_splash',
    'ad_inter',
    'ad_appopen',
    'ad_native',
    'ad_small_native',
    'ad_banner',
    'ad_qureka',
    'dialog_before_ad_show',
    'dialog_time_seconds',
    'need_internet',
    'redirect_other_app_status',
    'new_package_name',
    'new_app_name',
    'new_app_icon',
    'new_app_banner',
    'new_app_body',
    'new_app_link',
    'download_status',
    'background_status',
    'popup_status',
    'main_click_status',
    'update_dialog_status',
    'version_codes',
    'privacy_policy_url',
    'more_app_url',
    'others',
])]
class AdNetworkSetting extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'ad_show_status' => 'boolean',
            'admob_status' => 'boolean',
            'how_show_ad' => 'integer',
            'main_click_count' => 'integer',
            'inner_click_count' => 'integer',
            'dialog_before_ad_show' => 'boolean',
            'dialog_time_seconds' => 'integer',
            'need_internet' => 'boolean',
            'redirect_other_app_status' => 'boolean',
            'update_dialog_status' => 'boolean',
            'others' => 'array',
        ];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(AndroidApp::class, 'app_id');
    }

    public function toAndroidPayload(): array
    {
        $payload = [
            'app_name' => $this->app?->name ?? '',
            'app_db_id' => $this->app_id,
            'app_package_name' => $this->app?->package_name ?? '',
            'package_name' => $this->app?->package_name ?? '',
            'current_version' => $this->app?->current_version ?? '',
            'admob_interid' => $this->admob_interstitial_id ?? '',
            'admob_bannerid' => $this->admob_banner_id ?? '',
            'admob_medium_rectangleid' => $this->admob_medium_rectangle_id ?? $this->admob_banner_id ?? '',
            'admob_nativeid' => $this->admob_native_id ?? '',
            'admob_appopenid' => $this->admob_app_open_id ?? '',
            'adx_inter_id' => $this->adx_inter_id ?? '',
            'adx_banner_id' => $this->adx_banner_id ?? '',
            'adx_medium_rectangleid' => $this->adx_medium_rectangle_id ?? $this->adx_banner_id ?? '',
            'adx_native_id' => $this->adx_native_id ?? '',
            'adx_appopen_id' => $this->adx_app_open_id ?? '',
            'fb_inter_id' => $this->fb_inter_id ?? '',
            'fb_banner_id' => $this->fb_banner_id ?? '',
            'fb_medium_rectangle_id' => $this->fb_medium_rectangle_id ?? '',
            'fb_native_id' => $this->fb_native_id ?? '',
            'fb_native_banner_id' => $this->fb_native_banner_id ?? '',
            'wortise_app_id' => $this->wortise_app_id ?? '',
            'wortise_appopen_id' => $this->wortise_app_open_id ?? '',
            'wortise_inter_id' => $this->wortise_inter_id ?? '',
            'wortise_banner_id' => $this->wortise_banner_id ?? '',
            'wortise_medium_rectangle_id' => $this->wortise_medium_rectangle_id ?? '',
            'wortise_native_id' => $this->wortise_native_id ?? '',
            'inter_count' => (string) $this->main_click_count,
            'ad_splash' => $this->ad_splash ?? 'splash_appopen',
            'ad_inter' => $this->ad_inter ?? 'admob',
            'ad_appopen' => $this->ad_appopen ?? 'appopen',
            'ad_native' => $this->ad_native ?? 'admob',
            'ad_small_native' => $this->ad_small_native ?? 'admob',
            'ad_banner' => $this->ad_banner ?? 'admob',
            'ad_qureka' => $this->ad_qureka ?? 'off',
            'privacy_url' => $this->privacy_policy_url ?? '',
            'redirect_app' => $this->new_package_name ?? '',
            'new_app_name' => $this->new_app_name ?? '',
            'new_app_icon' => $this->new_app_icon ?? '',
            'new_app_banner' => $this->new_app_banner ?? '',
            'new_app_body' => $this->new_app_body ?? '',
            'new_app_link' => $this->new_app_link ?? '',
            'Download' => $this->download_status ?? 'off',
            'Backgraound' => $this->background_status ?? 'off',
            'Popup' => $this->popup_status ?? 'off',
            'Main_click' => $this->main_click_status ?? 'on',
        ];

        // Merge custom others key-value pairs into payload
        if (!empty($this->others) && is_array($this->others)) {
            foreach ($this->others as $pair) {
                if (!empty($pair['key'])) {
                    $payload[$pair['key']] = $pair['value'] ?? '';
                }
            }
        }

        return $payload;
    }

    public static function normalizePayload(array $data): array
    {
        $aliases = [
            'admob_interid' => 'admob_interstitial_id',
            'admob_bannerid' => 'admob_banner_id',
            'admob_medium_rectangleid' => 'admob_medium_rectangle_id',
            'admob_nativeid' => 'admob_native_id',
            'admob_appopenid' => 'admob_app_open_id',
            'adx_medium_rectangleid' => 'adx_medium_rectangle_id',
            'adx_appopen_id' => 'adx_app_open_id',
            'fb_medium_rectangle_id' => 'fb_medium_rectangle_id',
            'wortise_appopen_id' => 'wortise_app_open_id',
            'inter_count' => 'main_click_count',
            'privacy_url' => 'privacy_policy_url',
            'redirect_app' => 'new_package_name',
            'Download' => 'download_status',
            'Backgraound' => 'background_status',
            'Background' => 'background_status',
            'Popup' => 'popup_status',
            'Main_click' => 'main_click_status',
        ];

        foreach ($aliases as $from => $to) {
            if (array_key_exists($from, $data) && ! array_key_exists($to, $data)) {
                $data[$to] = $data[$from];
            }
        }

        return collect($data)->only((new self)->getFillable())->all();
    }
}
