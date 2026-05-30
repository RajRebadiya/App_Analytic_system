@extends('admin.layouts.app', ['title' => $app->exists ? 'Edit App' : 'Add App', 'heading' => $app->exists ? 'Edit App' : 'Add App'])

@section('content')
<form class="cardx p-4" method="POST" action="{{ $app->exists ? route('admin.apps.update', $app) : route('admin.apps.store') }}">
    @csrf
    @if($app->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">App Name</label>
            <input class="form-control" name="name" value="{{ old('name', $app->name) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Version</label>
            <input class="form-control" name="version" value="{{ old('version', $app->latest_version ?? '1.0.0') }}" required>
        </div>
        <div class="col-12">
            <div class="alert alert-info mb-0">
                App ID, package name, API key, status, force update, maintenance mode, current version, latest version, and minimum supported version are handled automatically.
            </div>
        </div>
        <div class="col-12"><button class="btn btn-primary">Save App</button><a class="btn btn-outline-secondary" href="{{ route('admin.apps.index') }}">Cancel</a></div>
    </div>
</form>
@endsection
