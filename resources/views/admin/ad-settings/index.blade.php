@extends('admin.layouts.app', ['title' => 'Ad Settings', 'heading' => 'Ad Network Settings'])

@section('actions')
<a class="btn btn-primary" href="{{ route('admin.ad-settings.create') }}">Add Ad Settings</a>
@endsection

@section('content')
<form class="cardx p-3 mb-3" method="GET">
    <div class="row g-2">
        <div class="col-md-4">
            <select class="form-select" name="app_id">
                <option value="">All apps</option>
                @foreach($apps as $app)
                    <option value="{{ $app->id }}" @selected(request('app_id') == $app->id)>{{ $app->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
    </div>
</form>

<div class="cardx p-3">
    <table class="table js-data-table">
        <thead><tr><th>App</th><th>AdMob App ID</th><th>Ad Status</th><th>Sequence</th><th>Clicks</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($settings as $setting)
            <tr>
                <td><strong>{{ $setting->app?->name }}</strong><div class="text-muted small">{{ $setting->app?->app_id }}</div></td>
                <td class="small">{{ $setting->admob_app_id }}</td>
                <td>
                    <span class="badge {{ $setting->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">Config {{ $setting->is_active ? 'Active' : 'Inactive' }}</span>
                    <span class="badge {{ $setting->ad_show_status ? 'text-bg-success' : 'text-bg-secondary' }}">Ads {{ $setting->ad_show_status ? 'On' : 'Off' }}</span>
                    <span class="badge {{ $setting->admob_status ? 'text-bg-success' : 'text-bg-secondary' }}">AdMob {{ $setting->admob_status ? 'On' : 'Off' }}</span>
                </td>
                <td>{{ $setting->ad_platform_sequence }}</td>
                <td>Main {{ $setting->main_click_count }} / Inner {{ $setting->inner_click_count }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.ad-settings.edit', $setting) }}">Edit</a>
                    <form class="d-inline" method="POST" action="{{ route('admin.ad-settings.destroy', $setting) }}" data-confirm="Delete these ad settings?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('admin.components.pagination', ['paginator' => $settings])
</div>
@endsection
