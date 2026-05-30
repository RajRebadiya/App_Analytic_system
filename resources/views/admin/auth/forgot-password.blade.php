<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{min-height:100vh;background:#f6f8fb;display:grid;place-items:center}.auth-card{width:min(420px,92vw);background:white;border:1px solid #e5e7eb;border-radius:8px;padding:28px}</style>
</head>
<body>
<form class="auth-card" method="POST" action="{{ route('admin.password.email') }}">
    @csrf
    <h1 class="h4 mb-3">Reset Password</h1>
    @include('admin.components.alerts')
    <label class="form-label">Email</label>
    <input class="form-control mb-3" name="email" type="email" required>
    <button class="btn btn-primary w-100">Send Reset Link</button>
    <a class="d-block mt-3" href="{{ route('admin.login') }}">Back to login</a>
</form>
</body>
</html>
