@extends('admin.layouts.app', ['title' => 'Dashboard', 'heading' => 'Analytics Overview', 'subtitle' => 'Monitor your app performance and user engagement in real-time.'])

@section('content')
<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 mb-6 sm:mb-8">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 items-end">
        <div class="sm:col-span-1">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Select App</label>
            <div class="relative">
                <select name="app_id" class="block w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm appearance-none">
                    <option value="">All Applications</option>
                    @foreach ($apps as $app)
                        <option value="{{ $app->id }}" @selected(($filters['app_id'] ?? '') == $app->id)>{{ $app->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
        <div class="sm:col-span-2 relative">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Date Range</label>
            <div class="relative date-range-picker-container" data-from="{{ $filters['from'] ?? '' }}" data-to="{{ $filters['to'] ?? '' }}">
                <input type="hidden" name="from" value="{{ $filters['from'] ?? '' }}">
                <input type="hidden" name="to" value="{{ $filters['to'] ?? '' }}">
                <button type="button" class="daterange-trigger flex items-center justify-between w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 text-sm font-semibold text-slate-700 shadow-sm cursor-pointer">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                        <span class="daterange-display-text text-slate-600 font-medium">Select Date Range</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-200"></i>
                </button>
                
                <div class="daterange-dropdown hidden absolute left-0 right-0 z-50 mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl">
                    <div class="absolute -top-1.5 left-6 w-3 h-3 bg-white border-t border-l border-slate-200 rotate-45"></div>
                    <div class="py-2 relative bg-white rounded-2xl overflow-hidden shadow-inner">
                        <button type="button" data-range="today" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Today</button>
                        <button type="button" data-range="yesterday" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Yesterday</button>
                        <button type="button" data-range="last_7" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Last 7 Days</button>
                        <button type="button" data-range="last_30" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Last 30 Days</button>
                        <button type="button" data-range="this_month" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">This Month</button>
                        <button type="button" data-range="last_month" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Last Month</button>
                        <button type="button" data-range="custom" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium border-t border-slate-100">Custom Range</button>
                    </div>
                </div>
                <input type="text" class="daterange-flatpickr absolute opacity-0 pointer-events-none inset-0 w-full h-full">
            </div>
        </div>
        <div class="sm:col-span-2 lg:col-span-1">
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-200 transition-all duration-200">
                <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                Update Analytics
            </button>
        </div>
    </form>
</div>


<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @php
        $stats = [
            ['Total Apps', $cards['total_apps'], 'smartphone', 'text-blue-600', 'bg-blue-100'],
            ['Total Installs', number_format($cards['total_installations']), 'download-cloud', 'text-indigo-600', 'bg-indigo-100'],
            ['Today Installs', number_format($cards['today_installations']), 'trending-up', 'text-emerald-600', 'bg-emerald-100'],
            ['Daily Active', number_format($cards['daily_active_users']), 'users', 'text-violet-600', 'bg-violet-100'],
            ['Monthly Active', number_format($cards['monthly_active_users']), 'calendar', 'text-amber-600', 'bg-amber-100'],
            ['Notifications', number_format($cards['total_notifications']), 'bell', 'text-rose-600', 'bg-rose-100'],
            ['Active Ads', $cards['active_advertisements'], 'layout', 'text-slate-600', 'bg-slate-100'],
        ];
    @endphp

    @foreach ($stats as [$label, $value, $icon, $color, $bg])
        <div class="group bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="{{ $bg }} {{ $color }} p-3 rounded-xl group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">{{ $label }}</p>
                <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $value }}</h3>
            </div>
        </div>
    @endforeach
</div>

<!-- Charts Row 1 -->
<div class="grid grid-cols-1 gap-8 mb-8">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900">Installation Trend</h3>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full">Last 30 Days</span>
        </div>
        <div class="relative h-[300px]">
            <canvas id="installChart"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900">Active User Activity</h3>
            <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full">Real-time</span>
        </div>
        <div class="relative h-[300px]">
            <canvas id="activityChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm overflow-hidden flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900">Recent API Activity</h3>
            <a href="{{ route('admin.api-logs.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">View All</a>
        </div>
        <div class="flex-1 space-y-4 overflow-y-auto pr-2 custom-scrollbar">
            @forelse ($recentActivities as $activity)
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-indigo-100 transition-colors">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded {{ $activity->method === 'GET' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $activity->method }}
                            </span>
                            <span class="text-sm font-semibold text-slate-700 truncate">{{ $activity->path }}</span>
                        </div>
                        <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="ml-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $activity->status_code >= 400 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $activity->status_code }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-center p-8">
                    <div class="bg-slate-100 p-3 rounded-full mb-3 text-slate-400">
                        <i data-lucide="activity" class="w-8 h-8"></i>
                    </div>
                    <p class="text-sm text-slate-500 font-medium">No activity recorded yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1e293b',
                titleFont: { size: 13, weight: 'bold' },
                padding: 12,
                cornerRadius: 12,
                displayColors: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9', drawBorder: false },
                ticks: { font: { size: 11 }, color: '#64748b' }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 11 }, color: '#64748b' }
            }
        }
    };

    const makeLine = (id, labels, data, label, color) => {
        const ctx = document.getElementById(id).getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, color + '33');
        gradient.addColorStop(1, color + '00');

        return new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label,
                    data,
                    borderColor: color,
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: color,
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: chartDefaults
        });
    };

    makeLine('installChart', @json($charts['installLabels']), @json($charts['installData']), 'Installs', '#4f46e5');
    makeLine('activityChart', @json($charts['activityLabels']), @json($charts['activityData']), 'Active Users', '#10b981');
});
</script>
@endpush
