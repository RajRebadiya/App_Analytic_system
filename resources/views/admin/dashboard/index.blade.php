@extends('admin.layouts.app', ['title' => 'Dashboard', 'heading' => 'Analytics Dashboard'])

@section('content')
<form class="cardx p-3 mb-3" method="GET">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">App</label>
            <select class="form-select" name="app_id">
                <option value="">All apps</option>
                @foreach ($apps as $app)
                    <option value="{{ $app->id }}" @selected(($filters['app_id'] ?? '') == $app->id)>{{ $app->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3"><label class="form-label">From</label><input class="form-control" name="from" type="date" value="{{ $filters['from'] ?? '' }}"></div>
        <div class="col-md-3"><label class="form-label">To</label><input class="form-control" name="to" type="date" value="{{ $filters['to'] ?? '' }}"></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Filter</button></div>
    </div>
</form>

<div class="row g-3 mb-3">
    @foreach ([
        'Total Apps' => $cards['total_apps'],
        'Total Installs' => $cards['total_installations'],
        'Today Installs' => $cards['today_installations'],
        'DAU' => $cards['daily_active_users'],
        'MAU' => $cards['monthly_active_users'],
        'Notifications Sent' => $cards['total_notifications_sent'],
        'Success Rate' => $cards['notification_success_rate'].'%',
        'Active Ads' => $cards['active_advertisements'],
    ] as $label => $value)
        <div class="col-sm-6 col-xl-3"><div class="cardx metric"><span>{{ $label }}</span><strong>{{ $value }}</strong></div></div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-8"><div class="cardx p-3"><h2 class="h6">Install Trend</h2><canvas id="installChart" height="120"></canvas></div></div>
    <div class="col-lg-4"><div class="cardx p-3"><h2 class="h6">Events</h2><canvas id="eventChart" height="260"></canvas></div></div>
    <div class="col-lg-8"><div class="cardx p-3"><h2 class="h6">Active Users</h2><canvas id="activityChart" height="120"></canvas></div></div>
    <div class="col-lg-4">
        <div class="cardx p-3 h-100">
            <h2 class="h6">Recent Activity</h2>
            <div class="list-group list-group-flush">
                @forelse ($recentActivities as $activity)
                    <div class="list-group-item px-0 d-flex justify-content-between">
                        <span>{{ $activity->method }} {{ $activity->path }}</span>
                        <span class="badge {{ $activity->status_code >= 400 ? 'text-bg-danger' : 'text-bg-success' }}">{{ $activity->status_code }}</span>
                    </div>
                @empty
                    <div class="text-muted">No API activity yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const makeLine = (id, labels, data, label, color) => new Chart(document.getElementById(id), {
    type: 'line',
    data: { labels, datasets: [{ label, data, borderColor: color, backgroundColor: color + '22', fill: true, tension: .35 }] },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
makeLine('installChart', @json($charts['installLabels']), @json($charts['installData']), 'Installs', '#2563eb');
makeLine('activityChart', @json($charts['activityLabels']), @json($charts['activityData']), 'Active Users', '#059669');
new Chart(document.getElementById('eventChart'), {
    type: 'doughnut',
    data: { labels: @json($charts['eventLabels']), datasets: [{ data: @json($charts['eventData']), backgroundColor: ['#2563eb','#059669','#d97706','#dc2626','#7c3aed'] }] },
    options: { responsive: true }
});
</script>
@endpush
