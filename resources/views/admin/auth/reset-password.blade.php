<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{min-height:100vh;background:#f6f8fb;display:grid;place-items:center}.auth-card{width:min(420px,92vw);background:white;border:1px solid #e5e7eb;border-radius:8px;padding:28px}</style>
</head>
<body>
<form class="auth-card" method="POST" action="{{ route('admin.password.update.reset') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <h1 class="h4 mb-3">Create New Password</h1>
    @include('admin.components.alerts')
    <label class="form-label">Email</label>
    <input class="form-control mb-3" name="email" type="email" value="{{ old('email', $email) }}" required>
    <label class="form-label">Password</label>
    <input class="form-control mb-3" name="password" type="password" required>
    <label class="form-label">Confirm Password</label>
    <input class="form-control mb-3" name="password_confirmation" type="password" required>
    <button class="btn btn-primary w-100">Reset Password</button>
</form>
</body>
</html>
