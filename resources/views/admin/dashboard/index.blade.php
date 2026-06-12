@extends('admin.layouts.app', ['title' => 'Dashboard', 'heading' => 'Analytics Overview', 'subtitle' => 'Monitor your app performance and user engagement in real-time.'])

@section('content')
<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div>
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
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">From Date</label>
            <input name="from" type="date" value="{{ $filters['from'] ?? '' }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">To Date</label>
            <input name="to" type="date" value="{{ $filters['to'] ?? '' }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm">
        </div>
        <div>
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
            ['Notifications', number_format($cards['total_notifications_sent']), 'bell', 'text-rose-600', 'bg-rose-100'],
            ['Success Rate', $cards['notification_success_rate'].'%', 'check-circle-2', 'text-cyan-600', 'bg-cyan-100'],
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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900">Installation Trend</h3>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full">Last 30 Days</span>
        </div>
        <div class="relative h-[300px]">
            <canvas id="installChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-6">Event Distribution</h3>
        <div class="relative h-[300px] flex items-center justify-center">
            <canvas id="eventChart"></canvas>
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

    new Chart(document.getElementById('eventChart'), {
        type: 'doughnut',
        data: {
            labels: @json($charts['eventLabels']),
            datasets: [{
                data: @json($charts['eventData']),
                backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                borderWidth: 4,
                borderColor: '#fff',
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, weight: '600' }
                    }
                }
            }
        }
    });
});
</script>
@endpush

