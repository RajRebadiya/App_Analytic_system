@extends('admin.layouts.app', ['title' => 'Active Users', 'heading' => 'DAU / MAU Analytics'])

@section('content')
@include('admin.analytics.partials.filters')
<div class="row g-3">
    <div class="col-lg-8"><div class="cardx p-3"><h2 class="h6">Daily Active Users</h2><canvas id="dauChart" height="120"></canvas></div></div>
    <div class="col-lg-4"><div class="cardx p-3"><h2 class="h6">Active Users by Version</h2><canvas id="versionChart" height="230"></canvas></div></div>
    <div class="col-12">
        <div class="cardx p-3">
            <h2 class="h6">Active Users by App</h2>
            <table class="table"><thead><tr><th>App</th><th>Active Users</th></tr></thead><tbody>@foreach($byApp as $row)<tr><td>{{ $row->name }}</td><td>{{ $row->total }}</td></tr>@endforeach</tbody></table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('dauChart'), {type:'line', data:{labels:@json($daily->pluck('label')), datasets:[{data:@json($daily->pluck('total')), borderColor:'#059669', backgroundColor:'#05966922', fill:true, tension:.35}]}, options:{plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}}}});
new Chart(document.getElementById('versionChart'), {type:'bar', data:{labels:@json($byVersion->pluck('app_version')), datasets:[{data:@json($byVersion->pluck('total')), backgroundColor:'#7c3aed'}]}, options:{indexAxis:'y', plugins:{legend:{display:false}}}});
</script>
@endpush
