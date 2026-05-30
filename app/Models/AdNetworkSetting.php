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
    'admob_interstitial_id',
    'admob_native_id',
    'admob_rewarded_id',
    'how_show_ad',
    'ad_platform_sequence',
    'alternate_ad_show',
    'main_click_count',
    'inner_click_count',
    'dialog_before_ad_show',
    'dialog_time_seconds',
    'need_internet',
    'redirect_other_app_status',
    'new_package_name',
    'update_dialog_status',
    'version_codes',
    'privacy_policy_url',
    'more_app_url',
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
        ];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(AndroidApp::class, 'app_id');
    }

    public function toAndroidPayload(): array
    {
        return [
            'is_active' => $this->is_active,
            'app_privacyPolicyLink' => $this->privacy_policy_url ?? '',
            'app_needInternet' => (int) $this->need_internet,
            'app_updateAppDialogStatus' => (int) $this->update_dialog_status,
            'app_versionCode' => $this->version_codes ?? '',
            'app_redirectOtherAppStatus' => (int) $this->redirect_other_app_status,
            'app_newPackageName' => $this->new_package_name ?? '',
            'app_dialogBeforeAdShow' => (int) $this->dialog_before_ad_show,
            'app_adShowStatus' => (int) $this->ad_show_status,
            'app_howShowAd' => $this->how_show_ad,
            'app_adPlatformSequence' => $this->ad_platform_sequence ?? '',
            'app_alernateAdShow' => $this->alternate_ad_show ?? '',
            'app_mainClickCntSwAd' => $this->main_click_count,
            'app_innerClickCntSwAd' => $this->inner_click_count,
            'ad_dialog_time_in_second' => $this->dialog_time_seconds,
            'am_ad_showAdStatus' => (int) $this->admob_status,
            'am_AppID' => $this->admob_app_id ?? '',
            'am_Banner1' => $this->admob_banner_id ?? '',
            'am_Interstitial1' => $this->admob_interstitial_id ?? '',
            'am_Native1' => $this->admob_native_id ?? '',
            'am_RewardedVideo1' => $this->admob_rewarded_id ?? '',
            'MORE_APP' => $this->more_app_url ?? '',
        ];
    }
}
