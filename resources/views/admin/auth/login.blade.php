<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{min-height:100vh;background:#f6f8fb;display:grid;place-items:center}.auth-card{width:min(420px,92vw);background:white;border:1px solid #e5e7eb;border-radius:8px;padding:28px;box-shadow:0 10px 30px rgba(15,23,42,.08)}</style>
</head>
<body>
<form class="auth-card" method="POST" action="{{ route('admin.login.store') }}">
    @csrf
    <h1 class="h4 mb-1">Admin Login</h1>
    <p class="text-muted mb-4">Manage apps, analytics, ads, and notifications.</p>
    @include('admin.components.alerts')
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" name="email" type="email" value="{{ old('email') }}" required autofocus>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control" name="password" type="password" required>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <label class="form-check"><input class="form-check-input" type="checkbox" name="remember"> <span class="form-check-label">Remember me</span></label>
        <a href="{{ route('admin.password.request') }}">Forgot password?</a>
    </div>
    <button class="btn btn-primary w-100">Login</button>
    <div class="text-center mt-3">
        <span class="text-muted">Need an admin account?</span>
        <a href="{{ route('admin.register') }}">Register</a>
    </div>
</form>
</body>
</html>
