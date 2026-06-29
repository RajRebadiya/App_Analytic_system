@extends('admin.layouts.app', ['title' => 'Ad Settings', 'heading' => 'Ad Network Configuration', 'subtitle' => 'Manage ad network IDs and runtime rules for each application.'])

@section('actions')
<a href="{{ route('admin.ad-settings.create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent shadow-lg shadow-indigo-200 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200">
    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
    Add Configuration
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-4 mb-8">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
        <div class="sm:col-span-3">
            <label class="block text-sm font-bold text-slate-700 mb-2">Filter by Application</label>
            <div class="relative">
                <select name="app_id" class="block w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm appearance-none">
                    <option value="">All Applications</option>
                    @foreach($apps as $app)
                        <option value="{{ $app->id }}" @selected(request('app_id') == $app->id)>{{ $app->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
        <div>
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 border border-slate-200 text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-all duration-200">
                <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                Apply Filter
            </button>
        </div>
    </form>
</div>


<!-- Settings Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Application</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">AdMob App ID</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status Indicators</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Rules</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Others</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach($settings as $setting)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $setting->app?->name }}</div>
                            <div class="text-xs text-slate-500">{{ $setting->app?->package_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-xs font-mono text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ $setting->admob_app_id ?: 'Not Set' }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $setting->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    Config
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $setting->ad_show_status ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                                    Ads
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $setting->admob_status ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500' }}">
                                    AdMob
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs font-bold text-slate-700 mb-1">{{ $setting->ad_platform_sequence }}</div>
                            <div class="text-[10px] text-slate-400">Click: {{ $setting->main_click_count }} / {{ $setting->inner_click_count }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php $others = $setting->others ?? []; @endphp
                            @if(!empty($others))
                                <div class="flex flex-wrap gap-1">
                                    @foreach($others as $pair)
                                        @if(!empty($pair['key']))
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-violet-50 text-violet-700 border border-violet-100" title="{{ $pair['value'] ?? '' }}">
                                                <span class="text-violet-400">{{ $pair['key'] }}</span>
                                                <span class="text-slate-400">:</span>
                                                <span class="text-violet-600 max-w-[80px] truncate">{{ $pair['value'] ?? '—' }}</span>
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span class="text-[10px] text-slate-300 font-medium">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <a href="{{ route('admin.ad-settings.edit', $setting) }}" 
                                   class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                   title="Edit Configuration">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.ad-settings.destroy', $setting) }}" data-confirm="Are you sure you want to delete these settings?">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                            title="Delete Configuration">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('admin.components.pagination', ['paginator' => $settings])
</div>
@endsection

