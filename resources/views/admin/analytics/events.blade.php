@extends('admin.layouts.app', ['title' => 'Event Analytics', 'heading' => 'Event Analytics'])

@section('content')
<form class="cardx p-3 mb-3" method="GET">
    <div class="row g-2">
        <div class="col-md-4"><select class="form-select" name="app_id"><option value="">All apps</option>@foreach($apps as $app)<option value="{{ $app->id }}" @selected(request('app_id') == $app->id)>{{ $app->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><select class="form-select" name="event_name"><option value="">All events</option>@foreach(['app_open','screen_view','ad_click','notification_open','button_click'] as $event)<option value="{{ $event }}" @selected(request('event_name') === $event)>{{ $event }}</option>@endforeach</select></div>
        <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
    </div>
</form>
<div class="row g-3">
    <div class="col-lg-4"><div class="cardx p-3"><h2 class="h6">Event Breakdown</h2><canvas id="eventBreakdown" height="240"></canvas></div></div>
    <div class="col-lg-8"><div class="cardx p-3"><table class="table js-data-table"><thead><tr><th>App</th><th>Device</th><th>Event</th><th>Data</th><th>Time</th></tr></thead><tbody>@foreach($events as $event)<tr><td>{{ $event->app?->name }}</td><td>{{ $event->device_id }}</td><td><span class="badge badge-soft">{{ $event->event_name }}</span></td><td class="small">{{ json_encode($event->event_data) }}</td><td>{{ $event->created_at }}</td></tr>@endforeach</tbody></table>@include('admin.components.pagination', ['paginator' => $events])</div></div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('eventBreakdown'), {type:'doughnut', data:{labels:@json($breakdown->pluck('event_name')), datasets:[{data:@json($breakdown->pluck('total')), backgroundColor:['#2563eb','#059669','#d97706','#dc2626','#7c3aed']}] }});
</script>
@endpush
