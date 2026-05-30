@extends('admin.layouts.app', ['title' => 'Advertisements', 'heading' => 'Advertisement Management'])

@section('actions')<a class="btn btn-primary" href="{{ route('admin.advertisements.create') }}">Add Advertisement</a>@endsection

@section('content')
<form class="cardx p-3 mb-3" method="GET">
    <div class="row g-2">
        <div class="col-md-4"><select class="form-select" name="app_id"><option value="">All apps</option>@foreach($apps as $app)<option value="{{ $app->id }}" @selected(request('app_id') == $app->id)>{{ $app->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
    </div>
</form>
<div class="cardx p-3">
    <table class="table js-data-table">
        <thead><tr><th>Image</th><th>Title</th><th>App</th><th>Redirect</th><th>Schedule</th><th>Priority</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @foreach($advertisements as $ad)
            <tr>
                <td>@if($ad->image)<img class="preview-img" src="{{ str_starts_with($ad->image, 'http') ? $ad->image : asset('storage/'.$ad->image) }}">@endif</td>
                <td><strong>{{ $ad->title }}</strong><div class="text-muted small">{{ $ad->description }}</div></td>
                <td>{{ $ad->app?->name }}</td>
                <td><span class="badge badge-soft">{{ $ad->redirect_type }}</span> {{ $ad->redirect_value }}</td>
                <td class="small">{{ optional($ad->start_date)->format('Y-m-d') }} - {{ optional($ad->end_date)->format('Y-m-d') }}</td>
                <td>{{ $ad->priority }}</td>
                <td><span class="badge {{ $ad->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $ad->status }}</span></td>
                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.advertisements.edit', $ad) }}">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.advertisements.destroy', $ad) }}" data-confirm="Delete this ad?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('admin.components.pagination', ['paginator' => $advertisements])
</div>
@endsection
