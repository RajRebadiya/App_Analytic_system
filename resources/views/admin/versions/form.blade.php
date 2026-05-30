@extends('admin.layouts.app', ['title' => $version->exists ? 'Edit Version' : 'Add Version', 'heading' => $version->exists ? 'Edit Version' : 'Add Version'])

@section('content')
<form class="cardx p-4" method="POST" action="{{ $version->exists ? route('admin.versions.update', $version) : route('admin.versions.store') }}">
    @csrf
    @if($version->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">App</label><select class="form-select" name="app_id" required>@foreach($apps as $app)<option value="{{ $app->id }}" @selected(old('app_id', $version->app_id) == $app->id)>{{ $app->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Latest Version</label><input class="form-control" name="latest_version" value="{{ old('latest_version', $version->latest_version) }}" required></div>
        <div class="col-md-4"><label class="form-label">Minimum Supported Version</label><input class="form-control" name="min_supported_version" value="{{ old('min_supported_version', $version->min_supported_version) }}" required></div>
        <div class="col-md-6"><label class="form-label">APK URL</label><input class="form-control" name="apk_url" value="{{ old('apk_url', $version->apk_url) }}"></div>
        <div class="col-md-3 d-flex align-items-end"><label class="form-check"><input type="hidden" name="force_update" value="0"><input class="form-check-input" type="checkbox" name="force_update" value="1" @checked(old('force_update', $version->force_update))> <span class="form-check-label">Force Update</span></label></div>
        <div class="col-md-3 d-flex align-items-end"><label class="form-check"><input type="hidden" name="maintenance_mode" value="0"><input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" @checked(old('maintenance_mode', $version->maintenance_mode))> <span class="form-check-label">Maintenance Mode</span></label></div>
        <div class="col-12"><label class="form-label">Update Popup Message</label><textarea class="form-control" name="message" rows="3">{{ old('message', $version->message) }}</textarea></div>
        <div class="col-12"><label class="form-label">Change Log</label><textarea class="form-control" name="change_log" rows="4">{{ old('change_log', $version->change_log) }}</textarea></div>
        <div class="col-12"><button class="btn btn-primary">Save Version</button><a class="btn btn-outline-secondary" href="{{ route('admin.versions.index') }}">Cancel</a></div>
    </div>
</form>
@endsection
