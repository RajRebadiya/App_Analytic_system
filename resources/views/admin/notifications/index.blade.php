@extends('admin.layouts.app', ['title' => 'Notifications', 'heading' => 'Notification Management'])

@section('actions')<a class="btn btn-primary" href="{{ route('admin.notifications.create') }}">Create Notification</a>@endsection

@section('content')
<form class="cardx p-3 mb-3" method="GET">
    <div class="row g-2"><div class="col-md-4"><select class="form-select" name="app_id"><option value="">All apps</option>@foreach($apps as $app)<option value="{{ $app->id }}" @selected(request('app_id') == $app->id)>{{ $app->name }}</option>@endforeach</select></div><div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div></div>
</form>
<div class="cardx p-3">
    <table class="table js-data-table">
        <thead><tr><th>Notification</th><th>App</th><th>Type</th><th>Sent</th><th>Failed</th><th>Logs</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($notifications as $notification)
            <tr>
                <td><strong>{{ $notification->title }}</strong><div class="text-muted small">{{ $notification->description }}</div></td>
                <td>{{ $notification->app?->name }}</td>
                <td><span class="badge badge-soft">{{ $notification->notification_type }}</span></td>
                <td>{{ $notification->total_sent }}</td>
                <td>{{ $notification->total_failed }}</td>
                <td><a href="{{ route('admin.notifications.logs', $notification) }}">{{ $notification->logs_count }}</a></td>
                <td class="text-end">
                    <form class="d-inline" method="POST" action="{{ route('admin.notifications.send', $notification) }}">@csrf<button class="btn btn-sm btn-success">Send</button></form>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.notifications.edit', $notification) }}">Edit</a>
                    <form class="d-inline" method="POST" action="{{ route('admin.notifications.destroy', $notification) }}" data-confirm="Delete this notification?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('admin.components.pagination', ['paginator' => $notifications])
</div>
@endsection
