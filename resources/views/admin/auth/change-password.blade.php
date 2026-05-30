@extends('admin.layouts.app', ['title' => 'Change Password', 'heading' => 'Change Password'])

@section('content')
<div class="cardx p-4">
    <form method="POST" action="{{ route('admin.password.update') }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-4"><label class="form-label">Current Password</label><input class="form-control" name="current_password" type="password" required></div>
        <div class="col-md-4"><label class="form-label">New Password</label><input class="form-control" name="password" type="password" required></div>
        <div class="col-md-4"><label class="form-label">Confirm Password</label><input class="form-control" name="password_confirmation" type="password" required></div>
        <div class="col-12"><button class="btn btn-primary">Update Password</button></div>
    </form>
</div>
@endsection
