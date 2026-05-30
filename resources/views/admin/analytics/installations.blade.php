@extends('admin.layouts.app', ['title' => 'Installation Analytics', 'heading' => 'Installation Analytics'])

@section('actions')<a class="btn btn-outline-secondary" href="{{ route('admin.analytics.installations.export', request()->query()) }}">Export CSV</a>@endsection

@section('content')
@include('admin.analytics.partials.filters')
<div class="row g-3 mb-3">
    <div class="col-lg-8"><div class="cardx p-3"><h2 class="h6">Daily Installs</h2><canvas id="dailyInstalls" height="120"></canvas></div></div>
    <div class="col-lg-4"><div class="cardx p-3"><h2 class="h6">Device Brands</h2><canvas id="deviceChart" height="230"></canvas></div></div>
</div>
<div class="cardx p-3">
    <table class="table js-data-table">
        <thead><tr><th>App</th><th>Device</th><th>Brand</th><th>Android</th><th>Version</th><th>Installed</th><th>Last Active</th></tr></thead>
        <tbody>@foreach($installations as $row)<tr><td>{{ $row->app?->name }}</td><td>{{ $row->device_id }}</td><td>{{ $row->device_brand }}</td><td>{{ $row->android_version }}</td><td>{{ $row->app_version }}</td><td>{{ $row->installed_at }}</td><td>{{ $row->last_active_at }}</td></tr>@endforeach</tbody>
    </table>
    @include('admin.components.pagination', ['paginator' => $installations])
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('dailyInstalls'), {type:'bar', data:{labels:@json($daily->pluck('label')), datasets:[{data:@json($daily->pluck('total')), backgroundColor:'#2563eb'}]}, options:{plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}}}});
new Chart(document.getElementById('deviceChart'), {type:'doughnut', data:{labels:@json($devices->pluck('device_brand')), datasets:[{data:@json($devices->pluck('total')), backgroundColor:['#2563eb','#059669','#d97706','#dc2626','#7c3aed','#0891b2']}] }});
</script>
@endpush
