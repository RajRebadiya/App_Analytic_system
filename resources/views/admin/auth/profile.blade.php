@extends('admin.layouts.app', ['title' => 'Profile', 'heading' => 'Profile Management'])

@section('content')
<div class="cardx p-4">
    <form method="POST" action="{{ route('admin.profile.update') }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-6">
            <label class="form-label">Name</label>
            <input class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" type="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Save Profile</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.password.change') }}">Change Password</a>
        </div>
    </form>
</div>
@endsection
