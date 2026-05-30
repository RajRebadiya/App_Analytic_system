<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdNetworkSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_id' => ['required', 'exists:apps,id'],
            'is_active' => ['boolean'],
            'ad_show_status' => ['boolean'],
            'admob_status' => ['boolean'],
            'admob_app_id' => ['nullable', 'string', 'max:255'],
            'admob_banner_id' => ['nullable', 'string', 'max:255'],
            'admob_interstitial_id' => ['nullable', 'string', 'max:255'],
            'admob_native_id' => ['nullable', 'string', 'max:255'],
            'admob_rewarded_id' => ['nullable', 'string', 'max:255'],
            'how_show_ad' => ['required', 'integer', 'in:0,1'],
            'ad_platform_sequence' => ['nullable', 'string', 'max:255'],
            'alternate_ad_show' => ['nullable', 'string', 'max:255'],
            'main_click_count' => ['required', 'integer', 'min:0'],
            'inner_click_count' => ['required', 'integer', 'min:0'],
            'dialog_before_ad_show' => ['boolean'],
            'dialog_time_seconds' => ['required', 'integer', 'min:0', 'max:60'],
            'need_internet' => ['boolean'],
            'redirect_other_app_status' => ['boolean'],
            'new_package_name' => ['nullable', 'string', 'max:255'],
            'update_dialog_status' => ['boolean'],
            'version_codes' => ['nullable', 'string', 'max:255'],
            'privacy_policy_url' => ['nullable', 'url', 'max:2048'],
            'more_app_url' => ['nullable', 'url', 'max:2048'],
        ];
    }
}
