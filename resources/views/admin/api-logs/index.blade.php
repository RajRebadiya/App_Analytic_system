@extends('admin.layouts.app', ['title' => 'API Logs', 'heading' => 'API Monitoring'])

@section('content')
<form class="cardx p-3 mb-3" method="GET">
    <div class="row g-2">
        <div class="col-md-5"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search path or IP"></div>
        <div class="col-md-3"><input class="form-control" name="status_code" value="{{ request('status_code') }}" placeholder="Status code"></div>
        <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
    </div>
</form>
<div class="cardx p-3">
    <table class="table js-data-table">
        <thead><tr><th>Method</th><th>Path</th><th>Status</th><th>Response Time</th><th>App</th><th>IP</th><th>Time</th></tr></thead>
        <tbody>
        @foreach($logs as $log)
            <tr><td>{{ $log->method }}</td><td>{{ $log->path }}</td><td><span class="badge {{ $log->status_code >= 400 ? 'text-bg-danger' : 'text-bg-success' }}">{{ $log->status_code }}</span></td><td>{{ $log->response_time_ms }} ms</td><td>{{ $log->app_id }}</td><td>{{ $log->ip_address }}</td><td>{{ $log->created_at }}</td></tr>
        @endforeach
        </tbody>
    </table>
    @include('admin.components.pagination', ['paginator' => $logs])
</div>
@endsection
