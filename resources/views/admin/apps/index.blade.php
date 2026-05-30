@extends('admin.layouts.app', ['title' => 'Apps', 'heading' => 'App Management'])

@section('actions')
<div class="d-flex gap-2">
    <a class="btn btn-outline-secondary" href="{{ route('admin.apps.export') }}">Export CSV</a>
    <a class="btn btn-primary" href="{{ route('admin.apps.create') }}">Add App</a>
</div>
@endsection

@section('content')
<form class="cardx p-3 mb-3" method="GET">
    <div class="input-group"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search app or package"><button class="btn btn-outline-secondary">Search</button></div>
</form>
<div class="cardx p-3">
    <table class="table js-data-table">
        <thead><tr><th>App</th><th>Package</th><th>API Key</th><th>Version</th><th>Flags</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach ($apps as $app)
            <tr>
                <td><strong>{{ $app->name }}</strong><div class="text-muted small">{{ $app->app_id }}</div></td>
                <td>{{ $app->package_name }}</td>
                <td>
                    <div class="input-group input-group-sm" style="min-width: 280px;">
                        <input class="form-control" value="{{ $app->api_key }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" data-copy="{{ $app->api_key }}">Copy</button>
                    </div>
                </td>
                <td>{{ $app->latest_version }} <span class="text-muted">min {{ $app->min_supported_version }}</span></td>
                <td>
                    <span class="badge {{ $app->force_update ? 'text-bg-danger' : 'text-bg-light' }}">Force</span>
                    <span class="badge {{ $app->maintenance_mode ? 'text-bg-warning' : 'text-bg-light' }}">Maintenance</span>
                </td>
                <td><span class="badge {{ $app->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $app->status }}</span></td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.apps.edit', $app) }}">Edit</a>
                    <form class="d-inline" method="POST" action="{{ route('admin.apps.status', [$app, $app->status === 'active' ? 'inactive' : 'active']) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-secondary">{{ $app->status === 'active' ? 'Suspend' : 'Activate' }}</button></form>
                    <form class="d-inline" method="POST" action="{{ route('admin.apps.rotate-key', $app) }}" data-confirm="Rotate this API key?">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-warning">Rotate Key</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('admin.components.pagination', ['paginator' => $apps])
</div>
@endsection

@push('scripts')
<script>
    $('[data-copy]').on('click', function () {
        navigator.clipboard.writeText($(this).data('copy'));
        $(this).text('Copied');
        setTimeout(() => $(this).text('Copy'), 1200);
    });
</script>
@endpush
