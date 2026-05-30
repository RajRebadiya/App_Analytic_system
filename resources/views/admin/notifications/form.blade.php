@extends('admin.layouts.app', ['title' => $notification->exists ? 'Edit Notification' : 'Create Notification', 'heading' => $notification->exists ? 'Edit Notification' : 'Create Notification'])

@section('content')
<form class="cardx p-4" method="POST" enctype="multipart/form-data" action="{{ $notification->exists ? route('admin.notifications.update', $notification) : route('admin.notifications.store') }}">
    @csrf
    @if($notification->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">App</label><select class="form-select" name="app_id" required>@foreach($apps as $app)<option value="{{ $app->id }}" @selected(old('app_id', $notification->app_id) == $app->id)>{{ $app->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ old('title', $notification->title) }}" required></div>
        <div class="col-md-2"><label class="form-label">Type</label><input class="form-control" name="notification_type" value="{{ old('notification_type', $notification->notification_type ?? 'general') }}"></div>
        <div class="col-md-2"><label class="form-label">Send To</label><select class="form-select" name="send_to"><option value="all" @selected(old('send_to', $notification->send_to ?? 'all') === 'all')>All</option><option value="active" @selected(old('send_to', $notification->send_to) === 'active')>Active</option></select></div>
        <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3">{{ old('description', $notification->description) }}</textarea></div>
        <div class="col-md-4"><label class="form-label">Image URL</label><input class="form-control" name="image" value="{{ old('image', $notification->image) }}"></div>
        <div class="col-md-4"><label class="form-label">Upload Image</label><input class="form-control" type="file" name="image_file" data-image-input="#notificationPreview"></div>
        <div class="col-md-4">@if($notification->image)<img id="notificationPreview" class="preview-img mt-4" src="{{ str_starts_with($notification->image, 'http') ? $notification->image : asset('storage/'.$notification->image) }}">@else<img id="notificationPreview" class="preview-img mt-4 d-none">@endif</div>
        <div class="col-md-4"><label class="form-label">Redirect Screen</label><input class="form-control" name="redirect_screen" value="{{ old('redirect_screen', $notification->redirect_screen) }}"></div>
        <div class="col-md-8"><label class="form-label">Redirect Data JSON</label><input class="form-control" name="redirect_data[key]" value="{{ old('redirect_data.key', $notification->redirect_data['key'] ?? '') }}" placeholder="Optional key/value payload"></div>
        @unless($notification->exists)
            <div class="col-12"><label class="form-check"><input class="form-check-input" type="checkbox" name="send_now" value="1"> <span class="form-check-label">Send instantly after save</span></label></div>
        @endunless
        <div class="col-12"><button class="btn btn-primary">Save Notification</button><a class="btn btn-outline-secondary" href="{{ route('admin.notifications.index') }}">Cancel</a></div>
    </div>
</form>
@endsection
