<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdNetworkSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'app_id' => $this->app_id,
            'is_active' => $this->is_active,
            'ad_show_status' => $this->ad_show_status,
            'admob_status' => $this->admob_status,
            'admob_app_id' => $this->admob_app_id,
            'admob_banner_id' => $this->admob_banner_id,
            'admob_interstitial_id' => $this->admob_interstitial_id,
            'admob_native_id' => $this->admob_native_id,
            'admob_rewarded_id' => $this->admob_rewarded_id,
            'how_show_ad' => $this->how_show_ad,
            'ad_platform_sequence' => $this->ad_platform_sequence,
            'alternate_ad_show' => $this->alternate_ad_show,
            'main_click_count' => $this->main_click_count,
            'inner_click_count' => $this->inner_click_count,
            'dialog_before_ad_show' => $this->dialog_before_ad_show,
            'dialog_time_seconds' => $this->dialog_time_seconds,
            'need_internet' => $this->need_internet,
            'redirect_other_app_status' => $this->redirect_other_app_status,
            'new_package_name' => $this->new_package_name,
            'update_dialog_status' => $this->update_dialog_status,
            'version_codes' => $this->version_codes,
            'privacy_policy_url' => $this->privacy_policy_url,
            'more_app_url' => $this->more_app_url,
            'android_payload' => $this->toAndroidPayload(),
            'created_at' => $this->created_at,
        ];
    }
}
