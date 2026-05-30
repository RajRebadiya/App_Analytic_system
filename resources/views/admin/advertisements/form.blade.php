@extends('admin.layouts.app', ['title' => $advertisement->exists ? 'Edit Advertisement' : 'Add Advertisement', 'heading' => $advertisement->exists ? 'Edit Advertisement' : 'Add Advertisement'])

@section('content')
<form class="cardx p-4" method="POST" enctype="multipart/form-data" action="{{ $advertisement->exists ? route('admin.advertisements.update', $advertisement) : route('admin.advertisements.store') }}">
    @csrf
    @if($advertisement->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">App</label><select class="form-select" name="app_id" required>@foreach($apps as $app)<option value="{{ $app->id }}" @selected(old('app_id', $advertisement->app_id) == $app->id)>{{ $app->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ old('title', $advertisement->title) }}" required></div>
        <div class="col-md-4"><label class="form-label">Status</label><select class="form-select" name="status"><option value="active" @selected(old('status', $advertisement->status ?? 'active') === 'active')>Active</option><option value="inactive" @selected(old('status', $advertisement->status) === 'inactive')>Inactive</option></select></div>
        <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3">{{ old('description', $advertisement->description) }}</textarea></div>
        <div class="col-md-4"><label class="form-label">Image URL</label><input class="form-control" name="image" value="{{ old('image', $advertisement->image) }}"></div>
        <div class="col-md-4"><label class="form-label">Upload Image</label><input class="form-control" type="file" name="image_file" data-image-input="#adPreview"></div>
        <div class="col-md-4">@if($advertisement->image)<img id="adPreview" class="preview-img mt-4" src="{{ str_starts_with($advertisement->image, 'http') ? $advertisement->image : asset('storage/'.$advertisement->image) }}">@else<img id="adPreview" class="preview-img mt-4 d-none">@endif</div>
        <div class="col-md-3"><label class="form-label">Redirect Type</label><select class="form-select" name="redirect_type"><option value="url" @selected(old('redirect_type', $advertisement->redirect_type) === 'url')>URL</option><option value="screen" @selected(old('redirect_type', $advertisement->redirect_type) === 'screen')>Screen</option><option value="category" @selected(old('redirect_type', $advertisement->redirect_type) === 'category')>Category</option><option value="product" @selected(old('redirect_type', $advertisement->redirect_type) === 'product')>Product</option></select></div>
        <div class="col-md-3"><label class="form-label">Redirect Value</label><input class="form-control" name="redirect_value" value="{{ old('redirect_value', $advertisement->redirect_value) }}"></div>
        <div class="col-md-2"><label class="form-label">Priority</label><input class="form-control" type="number" min="0" name="priority" value="{{ old('priority', $advertisement->priority ?? 0) }}"></div>
        <div class="col-md-2"><label class="form-label">Start</label><input class="form-control" type="datetime-local" name="start_date" value="{{ old('start_date', optional($advertisement->start_date)->format('Y-m-d\\TH:i')) }}"></div>
        <div class="col-md-2"><label class="form-label">End</label><input class="form-control" type="datetime-local" name="end_date" value="{{ old('end_date', optional($advertisement->end_date)->format('Y-m-d\\TH:i')) }}"></div>
        <div class="col-12"><button class="btn btn-primary">Save Advertisement</button><a class="btn btn-outline-secondary" href="{{ route('admin.advertisements.index') }}">Cancel</a></div>
    </div>
</form>
@endsection
