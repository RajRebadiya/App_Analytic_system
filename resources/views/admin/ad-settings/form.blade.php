@extends('admin.layouts.app', ['title' => $setting->exists ? 'Edit Ad Settings' : 'Add Ad Settings', 'heading' => $setting->exists ? 'Update Ad Configuration' : 'Create Ad Configuration', 'subtitle' => 'Configure ad network IDs, runtime behavior, and placement rules for your application.'])

@section('content')
@php
    $defaults = [
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
    ];

    $value = fn (string $field) => old($field, $setting->{$field} ?? $defaults[$field] ?? null);

    $textFields = [
        ['icon' => 'smartphone', 'title' => 'AdMob / Google IDs', 'fields' => [
            ['admob_app_id', 'AdMob App ID', 'ca-app-pub-xxx~yyy'],
            ['admob_interstitial_id', 'Interstitial ID', 'ca-app-pub-xxx/xxx'],
            ['admob_banner_id', 'Banner ID', 'ca-app-pub-xxx/xxx'],
            ['admob_medium_rectangle_id', 'Medium Rectangle ID', 'ca-app-pub-xxx/xxx'],
            ['admob_native_id', 'Native ID', 'ca-app-pub-xxx/xxx'],
            ['admob_rewarded_id', 'Rewarded Video ID', 'ca-app-pub-xxx/xxx'],
            ['admob_app_open_id', 'App Open ID', 'ca-app-pub-xxx/xxx'],
        ]],
        ['icon' => 'box', 'title' => 'AdX IDs', 'fields' => [
            ['adx_inter_id', 'AdX Interstitial ID', 'ID'],
            ['adx_banner_id', 'AdX Banner ID', 'ID'],
            ['adx_medium_rectangle_id', 'AdX Medium Rectangle ID', 'ID'],
            ['adx_native_id', 'AdX Native ID', 'ID'],
            ['adx_app_open_id', 'AdX App Open ID', 'ID'],
        ]],
        ['icon' => 'facebook', 'title' => 'Facebook Audience Network', 'fields' => [
            ['fb_inter_id', 'FB Interstitial ID', 'ID'],
            ['fb_banner_id', 'FB Banner ID', 'ID'],
            ['fb_medium_rectangle_id', 'FB Medium Rectangle ID', 'ID'],
            ['fb_native_id', 'FB Native ID', 'ID'],
            ['fb_native_banner_id', 'FB Native Banner ID', 'ID'],
        ]],
        ['icon' => 'zap', 'title' => 'Wortise IDs', 'fields' => [
            ['wortise_app_id', 'Wortise App ID', 'ID'],
            ['wortise_app_open_id', 'Wortise App Open ID', 'ID'],
            ['wortise_inter_id', 'Wortise Interstitial ID', 'ID'],
            ['wortise_banner_id', 'Wortise Banner ID', 'ID'],
            ['wortise_medium_rectangle_id', 'Wortise Medium Rectangle ID', 'ID'],
            ['wortise_native_id', 'Wortise Native ID', 'ID'],
        ]],
    ];

    $placementFields = [
        ['ad_splash', 'Splash Placement'],
        ['ad_inter', 'Interstitial Placement'],
        ['ad_appopen', 'App Open Placement'],
        ['ad_native', 'Native Placement'],
        ['ad_small_native', 'Small Native Placement'],
        ['ad_banner', 'Banner Placement'],
        ['ad_qureka', 'Qureka Placement'],
    ];

    $redirectFields = [
        ['new_package_name', 'Redirect Package', 'com.example.newapp'],
        ['new_app_name', 'New App Name', 'App Name'],
        ['new_app_icon', 'New App Icon URL', 'https://...'],
        ['new_app_banner', 'New App Banner URL', 'https://...'],
        ['new_app_link', 'New App Store Link', 'https://...'],
    ];
@endphp

<form action="{{ $setting->exists ? route('admin.ad-settings.update', $setting) : route('admin.ad-settings.store') }}" method="POST" class="space-y-12 pb-24">
    @csrf
    @if($setting->exists) @method('PUT') @endif

    <!-- Base Configuration Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Primary Configuration</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Basic status and application assignment.</p>
            </div>
            <div class="flex flex-wrap gap-4">
                @foreach([
                    'is_active' => 'Config Active',
                    'ad_show_status' => 'Show Ads',
                    'admob_status' => 'AdMob Active',
                    'dialog_before_ad_show' => 'Pre-Ad Dialog',
                ] as $field => $label)
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $setting->{$field})) class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm font-bold text-slate-700 group-hover:text-slate-900 transition-colors">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        
        <div class="p-8">
            <div class="max-w-md">
                <label for="app_id" class="block text-sm font-bold text-slate-700 mb-2">Target Application</label>
                <div class="relative">
                    <select name="app_id" id="app_id" required class="block w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm appearance-none">
                        @foreach($apps as $app)
                            <option value="{{ $app->id }}" @selected(old('app_id', $setting->app_id) == $app->id)>{{ $app->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Network IDs Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @foreach($textFields as $section)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden h-full flex flex-col">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="p-2 bg-white rounded-xl shadow-sm text-indigo-600 border border-slate-100">
                        <i data-lucide="{{ $section['icon'] }}" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ $section['title'] }}</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 flex-1">
                    @foreach($section['fields'] as [$field, $label, $placeholder])
                        <div class="{{ $loop->first && count($section['fields']) % 2 !== 0 ? 'md:col-span-2' : '' }}">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-tight mb-2">{{ $label }}</label>
                            <input type="text" name="{{ $field }}" value="{{ $value($field) }}" placeholder="{{ $placeholder }}"
                                   class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm @error($field) border-rose-300 bg-rose-50 @enderror">
                            @error($field)<p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Runtime Rules Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2 bg-white rounded-xl shadow-sm text-amber-600 border border-slate-100">
                <i data-lucide="play-circle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Runtime Ad Rules & Behavior</h3>
        </div>
        
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Display Logic</label>
                <select name="how_show_ad" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
                    <option value="0" @selected($value('how_show_ad') == 0)>Sequential Order</option>
                    <option value="1" @selected($value('how_show_ad') == 1)>Alternate Provider</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Primary Sequence</label>
                <input name="ad_platform_sequence" value="{{ $value('ad_platform_sequence') }}" placeholder="Admob" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Alternate Sequence</label>
                <input name="alternate_ad_show" value="{{ $value('alternate_ad_show') }}" placeholder="Admob" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pre-ad Timer (Sec)</label>
                <input type="number" name="dialog_time_seconds" value="{{ $value('dialog_time_seconds') }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Main Click Count</label>
                <input type="number" name="main_click_count" value="{{ $value('main_click_count') }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Inner Click Count</label>
                <input type="number" name="inner_click_count" value="{{ $value('inner_click_count') }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
            </div>
            <div class="flex items-center gap-6 md:col-span-2">
                <label class="flex items-center cursor-pointer group">
                    <input type="hidden" name="need_internet" value="0">
                    <input type="checkbox" name="need_internet" value="1" @checked(old('need_internet', $setting->need_internet)) class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                    <span class="ml-2 text-sm font-bold text-slate-700 group-hover:text-slate-900 transition-colors">Internet Required</span>
                </label>
                <label class="flex items-center cursor-pointer group">
                    <input type="hidden" name="update_dialog_status" value="0">
                    <input type="checkbox" name="update_dialog_status" value="1" @checked(old('update_dialog_status', $setting->update_dialog_status)) class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                    <span class="ml-2 text-sm font-bold text-slate-700 group-hover:text-slate-900 transition-colors">Show Update Dialog</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Placement Rules -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2 bg-white rounded-xl shadow-sm text-emerald-600 border border-slate-100">
                <i data-lucide="layout" class="w-5 h-5"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Placement Provider Overrides</h3>
        </div>
        <div class="p-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($placementFields as [$field, $label])
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">{{ $label }}</label>
                    <input name="{{ $field }}" value="{{ $value($field) }}" class="block w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm font-medium">
                </div>
            @endforeach
        </div>
    </div>

    <!-- App Controls & Redirect -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2 bg-white rounded-xl shadow-sm text-rose-600 border border-slate-100">
                <i data-lucide="external-link" class="w-5 h-5"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Advanced Controls & Redirects</h3>
        </div>
        <div class="p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Version Codes (CSV)</label>
                    <input name="version_codes" value="{{ $value('version_codes') }}" placeholder="1,2,3" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Privacy Policy URL</label>
                    <input name="privacy_policy_url" value="{{ $value('privacy_policy_url') }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">More Apps URL</label>
                    <input name="more_app_url" value="{{ $value('more_app_url') }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-sm font-bold text-slate-900">Redirect App Configuration</h4>
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="hidden" name="redirect_other_app_status" value="0">
                        <input type="checkbox" name="redirect_other_app_status" value="1" @checked(old('redirect_other_app_status', $setting->redirect_other_app_status)) class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-rose-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        <span class="ml-3 text-sm font-bold text-slate-700 group-hover:text-slate-900">Redirect Status</span>
                    </label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    @foreach($redirectFields as [$field, $label, $placeholder])
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-tight mb-2">{{ $label }}</label>
                            <input name="{{ $field }}" value="{{ $value($field) }}" placeholder="{{ $placeholder }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">
                        </div>
                    @endforeach
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-tight mb-2">New App Description Body</label>
                    <textarea name="new_app_body" rows="3" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm">{{ $value('new_app_body') }}</textarea>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach([
                    'download_status' => 'Download Button',
                    'background_status' => 'Background Mode',
                    'popup_status' => 'Popup Overlay',
                    'main_click_status' => 'Main Click Handler',
                ] as $field => $label)
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-tight mb-2">{{ $label }}</label>
                        <select name="{{ $field }}" class="block w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all duration-200 sm:text-sm font-bold">
                            <option value="on" @selected($value($field) === 'on')>ENABLED</option>
                            <option value="off" @selected($value($field) === 'off')>DISABLED</option>
                        </select>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 md:left-auto md:right-8 md:translate-x-0 z-40">
        <div class="bg-white/80 backdrop-blur-lg border border-slate-200 rounded-2xl shadow-2xl p-4 flex items-center gap-4">
            <a href="{{ route('admin.ad-settings.index') }}" class="px-6 py-3 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                Discard Changes
            </a>
            <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent shadow-lg shadow-indigo-200 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                Save Configuration
            </button>
        </div>
    </div>
</form>
@endsection

