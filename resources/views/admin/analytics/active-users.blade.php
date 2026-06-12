@extends('admin.layouts.app', ['title' => 'Active Users', 'heading' => 'User Engagement Analytics', 'subtitle' => 'Monitor Daily Active Users (DAU) and Monthly Active Users (MAU) across your apps.'])

@section('content')
@include('admin.analytics.partials.filters')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900">Daily Active Users</h3>
            <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full">Active Retention</span>
        </div>
        <div class="relative h-[300px]">
            <canvas id="dauChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-6">Users by App Version</h3>
        <div class="relative h-[300px]">
            <canvas id="versionChart"></canvas>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Active Users by Application</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Application</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Active User Count</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Share</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Growth</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @php $totalActive = $byApp->sum('total'); @endphp
                @foreach($byApp as $row)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mr-3 font-bold text-xs group-hover:scale-110 transition-transform">
                                    {{ substr($row->name, 0, 1) }}
                                </div>
                                <div class="text-sm font-bold text-slate-900">{{ $row->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-700">{{ number_format($row->total) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full bg-slate-100 rounded-full h-1.5 max-w-[100px]">
                                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $totalActive > 0 ? ($row->total / $totalActive) * 100 : 0 }}%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="inline-flex items-center text-xs font-bold text-emerald-600">
                                <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                                Stable
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    const ctx = document.getElementById('dauChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, '#10b98133');
    gradient.addColorStop(1, '#10b98100');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($daily->pluck('label')),
            datasets: [{
                label: 'Active Users',
                data: @json($daily->pluck('total')),
                borderColor: '#10b981',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointHoverRadius: 6
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

    new Chart(document.getElementById('versionChart'), {
        type: 'bar',
        data: {
            labels: @json($byVersion->pluck('app_version')),
            datasets: [{
                label: 'Users',
                data: @json($byVersion->pluck('total')),
                backgroundColor: '#8b5cf6',
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                y: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush

