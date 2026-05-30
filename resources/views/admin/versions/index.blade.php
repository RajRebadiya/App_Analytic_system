@extends('admin.layouts.app', ['title' => 'Versions', 'heading' => 'App Version Management'])

@section('actions')<a class="btn btn-primary" href="{{ route('admin.versions.create') }}">Add Version</a>@endsection

@section('content')
<form class="cardx p-3 mb-3" method="GET"><div class="row g-2"><div class="col-md-4"><select class="form-select" name="app_id"><option value="">All apps</option>@foreach($apps as $app)<option value="{{ $app->id }}" @selected(request('app_id') == $app->id)>{{ $app->name }}</option>@endforeach</select></div><div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div></div></form>
<div class="cardx p-3">
    <table class="table js-data-table">
        <thead><tr><th>App</th><th>Latest</th><th>Minimum</th><th>Flags</th><th>APK</th><th>Message</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($versions as $version)
            <tr>
                <td>{{ $version->app?->name }}</td>
                <td>{{ $version->latest_version }}</td>
                <td>{{ $version->min_supported_version }}</td>
                <td><span class="badge {{ $version->force_update ? 'text-bg-danger' : 'text-bg-light' }}">Force</span> <span class="badge {{ $version->maintenance_mode ? 'text-bg-warning' : 'text-bg-light' }}">Maintenance</span></td>
                <td class="small">{{ $version->apk_url }}</td>
                <td class="small">{{ $version->message }}</td>
                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.versions.edit', $version) }}">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.versions.destroy', $version) }}" data-confirm="Delete this version?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('admin.components.pagination', ['paginator' => $versions])
</div>
@endsection
