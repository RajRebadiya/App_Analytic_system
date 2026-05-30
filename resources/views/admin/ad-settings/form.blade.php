@extends('admin.layouts.app', ['title' => $setting->exists ? 'Edit Ad Settings' : 'Add Ad Settings', 'heading' => $setting->exists ? 'Edit Ad Settings' : 'Add Ad Settings'])

@section('content')
<form class="cardx p-4" method="POST" action="{{ $setting->exists ? route('admin.ad-settings.update', $setting) : route('admin.ad-settings.store') }}">
    @csrf
    @if($setting->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">App</label>
            <select class="form-select" name="app_id" required>
                @foreach($apps as $app)
                    <option value="{{ $app->id }}" @selected(old('app_id', $setting->app_id) == $app->id)>{{ $app->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-8 d-flex align-items-end gap-4">
            @foreach([
                'is_active' => 'Config Active',
                'ad_show_status' => 'Show Ads',
                'admob_status' => 'AdMob Active',
                'dialog_before_ad_show' => 'Dialog Before Ad',
            ] as $field => $label)
                <label class="form-check">
                    <input type="hidden" name="{{ $field }}" value="0">
                    <input class="form-check-input" type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $setting->{$field}))>
                    <span class="form-check-label">{{ $label }}</span>
                </label>
            @endforeach
        </div>

        <div class="col-12"><h2 class="h6 mb-0 mt-2">AdMob Credentials</h2></div>
        <div class="col-md-6"><label class="form-label">AdMob App ID</label><input class="form-control" name="admob_app_id" value="{{ old('admob_app_id', $setting->admob_app_id) }}"></div>
        <div class="col-md-6"><label class="form-label">Banner ID</label><input class="form-control" name="admob_banner_id" value="{{ old('admob_banner_id', $setting->admob_banner_id) }}"></div>
        <div class="col-md-4"><label class="form-label">Interstitial ID</label><input class="form-control" name="admob_interstitial_id" value="{{ old('admob_interstitial_id', $setting->admob_interstitial_id) }}"></div>
        <div class="col-md-4"><label class="form-label">Native ID</label><input class="form-control" name="admob_native_id" value="{{ old('admob_native_id', $setting->admob_native_id) }}"></div>
        <div class="col-md-4"><label class="form-label">Rewarded ID</label><input class="form-control" name="admob_rewarded_id" value="{{ old('admob_rewarded_id', $setting->admob_rewarded_id) }}"></div>

        <div class="col-12"><h2 class="h6 mb-0 mt-2">Runtime Ad Rules</h2></div>
        <div class="col-md-3">
            <label class="form-label">How Show Ad</label>
            <select class="form-select" name="how_show_ad">
                <option value="0" @selected(old('how_show_ad', $setting->how_show_ad) == 0)>Sequence</option>
                <option value="1" @selected(old('how_show_ad', $setting->how_show_ad) == 1)>Alternate</option>
            </select>
        </div>
        <div class="col-md-3"><label class="form-label">Platform Sequence</label><input class="form-control" name="ad_platform_sequence" value="{{ old('ad_platform_sequence', $setting->ad_platform_sequence ?? 'Admob') }}" placeholder="Admob"></div>
        <div class="col-md-3"><label class="form-label">Alternate Sequence</label><input class="form-control" name="alternate_ad_show" value="{{ old('alternate_ad_show', $setting->alternate_ad_show) }}" placeholder="Admob"></div>
        <div class="col-md-3"><label class="form-label">Dialog Seconds</label><input class="form-control" type="number" min="0" max="60" name="dialog_time_seconds" value="{{ old('dialog_time_seconds', $setting->dialog_time_seconds ?? 2) }}"></div>
        <div class="col-md-3"><label class="form-label">Main Click Count</label><input class="form-control" type="number" min="0" name="main_click_count" value="{{ old('main_click_count', $setting->main_click_count ?? 1) }}"></div>
        <div class="col-md-3"><label class="form-label">Inner Click Count</label><input class="form-control" type="number" min="0" name="inner_click_count" value="{{ old('inner_click_count', $setting->inner_click_count ?? 1) }}"></div>
        <div class="col-md-3 d-flex align-items-end"><label class="form-check"><input type="hidden" name="need_internet" value="0"><input class="form-check-input" type="checkbox" name="need_internet" value="1" @checked(old('need_internet', $setting->need_internet))> <span class="form-check-label">Need Internet</span></label></div>
        <div class="col-md-3 d-flex align-items-end"><label class="form-check"><input type="hidden" name="update_dialog_status" value="0"><input class="form-check-input" type="checkbox" name="update_dialog_status" value="1" @checked(old('update_dialog_status', $setting->update_dialog_status))> <span class="form-check-label">Update Dialog</span></label></div>

        <div class="col-12"><h2 class="h6 mb-0 mt-2">Extra App Controls</h2></div>
        <div class="col-md-4"><label class="form-label">Version Codes for Update Dialog</label><input class="form-control" name="version_codes" value="{{ old('version_codes', $setting->version_codes) }}" placeholder="1,2,3"></div>
        <div class="col-md-4"><label class="form-label">Privacy Policy URL</label><input class="form-control" name="privacy_policy_url" value="{{ old('privacy_policy_url', $setting->privacy_policy_url) }}"></div>
        <div class="col-md-4"><label class="form-label">More App URL</label><input class="form-control" name="more_app_url" value="{{ old('more_app_url', $setting->more_app_url) }}"></div>
        <div class="col-md-4 d-flex align-items-end"><label class="form-check"><input type="hidden" name="redirect_other_app_status" value="0"><input class="form-check-input" type="checkbox" name="redirect_other_app_status" value="1" @checked(old('redirect_other_app_status', $setting->redirect_other_app_status))> <span class="form-check-label">Redirect Other App</span></label></div>
        <div class="col-md-8"><label class="form-label">New Package Name</label><input class="form-control" name="new_package_name" value="{{ old('new_package_name', $setting->new_package_name) }}"></div>

        <div class="col-12"><button class="btn btn-primary">Save Ad Settings</button><a class="btn btn-outline-secondary" href="{{ route('admin.ad-settings.index') }}">Cancel</a></div>
    </div>
</form>
@endsection
