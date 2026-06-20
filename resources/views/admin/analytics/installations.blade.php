@extends('admin.layouts.app', ['title' => 'Installation Analytics', 'heading' => 'Installation Analytics', 'subtitle' => 'Track and analyze application installs, device distribution, and version adoption.'])

@section('actions')
<a href="{{ route('admin.analytics.installations.export', request()->query()) }}" class="inline-flex items-center px-4 py-2.5 border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-all duration-200">
    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
    Export Dataset
</a>
@endsection

@section('content')
@include('admin.analytics.partials.filters')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900">Daily Installs</h3>
            <span class="text-xs font-semibold px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-full">New Installs</span>
        </div>
        <div class="relative h-[300px]">
            <canvas id="dailyInstalls"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-6">Device Brand Distribution</h3>
        <div class="relative h-[300px] flex items-center justify-center">
            <canvas id="deviceChart"></canvas>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-slate-900">Geographic Distribution</h3>
        <span class="text-xs font-semibold px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-full">All Countries</span>
    </div>
    <div class="relative w-full h-[400px] flex items-center justify-center overflow-hidden" id="regions_div">
        <div class="text-slate-400 text-sm">Loading map data...</div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Application</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Device Identifier</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Country</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hardware / OS</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">App Version</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Timestamp</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach($installations as $row)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $row->app?->name }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $row->app?->package_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs font-medium text-slate-600 truncate max-w-[120px]" title="{{ $row->device_id }}">
                                {{ $row->device_id }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 rounded text-xs font-bold text-emerald-700">
                                {{ $row->country_code ?: 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-bold text-slate-600 uppercase">{{ $row->device_brand }}</span>
                                <span class="text-xs font-medium text-slate-500">Android {{ $row->android_version }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 rounded text-xs font-bold text-indigo-600">v{{ $row->app_version }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-[10px] font-bold text-slate-700 mb-0.5">Installed: {{ $row->installed_at?->format('d M, h:i A') ?: 'N/A' }}</div>
                            <div class="text-[10px] text-slate-400">Active: {{ $row->last_active_at?->diffForHumans() ?: 'N/A' }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('admin.components.pagination', ['paginator' => $installations])
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
$(function() {
    // Google GeoChart for Geographic Distribution
    google.charts.load('current', {
        'packages':['geochart'],
    });
    google.charts.setOnLoadCallback(drawRegionsMap);

    function drawRegionsMap() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Country');
        data.addColumn('number', 'Installs');

        var regionNames = null;
        try {
            regionNames = new Intl.DisplayNames(['en'], {type: 'region'});
        } catch (e) {}

        function getCountryDisplay(code) {
            if (regionNames && code) {
                try {
                    return regionNames.of(code) + ' (' + code + ')';
                } catch (e) {}
            }
            return code || 'Unknown';
        }

        data.addRows([
            @foreach($countries as $country)
            [{v: '{{ $country->country_code }}', f: getCountryDisplay('{{ $country->country_code }}')}, {{ $country->total }}],
            @endforeach
        ]);

        var options = {
            colorAxis: {colors: ['#e0e7ff', '#4f46e5']}, // Indigo 100 to Indigo 600
            backgroundColor: 'transparent',
            datalessRegionColor: '#f8fafc', // Slate 50
            defaultColor: '#f1f5f9',
            tooltip: {textStyle: {color: '#1e293b'}, showColorCode: true}
        };

        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
        chart.draw(data, options);
    }

    new Chart(document.getElementById('dailyInstalls'), {
        type: 'bar',
        data: {
            labels: @json($daily->pluck('label')),
            datasets: [{
                label: 'Installs',
                data: @json($daily->pluck('total')),
                backgroundColor: '#4f46e5',
                borderRadius: 8,
                hoverBackgroundColor: '#4338ca'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('deviceChart'), {
        type: 'doughnut',
        data: {
            labels: @json($devices->pluck('device_brand')),
            datasets: [{
                data: @json($devices->pluck('total')),
                backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                borderWidth: 4,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 20, font: { size: 11, weight: '600' } }
                }
            }
        }
    });
});
</script>
@endpush
