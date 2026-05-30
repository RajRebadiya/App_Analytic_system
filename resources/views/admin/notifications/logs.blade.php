@extends('admin.layouts.app', ['title' => 'Notification Logs', 'heading' => 'Notification Logs'])

@section('content')
<div class="cardx p-3 mb-3">
    <strong>{{ $notification->title }}</strong>
    <span class="text-muted ms-2">Sent {{ $notification->total_sent }} / Failed {{ $notification->total_failed }}</span>
</div>
<div class="cardx p-3">
    <table class="table js-data-table">
        <thead><tr><th>Device</th><th>Status</th><th>Sent At</th><th>Response</th></tr></thead>
        <tbody>
        @foreach($logs as $log)
            <tr><td>{{ $log->device_id }}</td><td><span class="badge {{ $log->status === 'sent' ? 'text-bg-success' : 'text-bg-danger' }}">{{ $log->status }}</span></td><td>{{ $log->sent_at }}</td><td class="small text-muted">{{ json_encode($log->response) }}</td></tr>
        @endforeach
        </tbody>
    </table>
    @include('admin.components.pagination', ['paginator' => $logs])
</div>
@endsection
