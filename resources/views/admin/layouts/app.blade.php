<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} | App Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root { --sidebar: #111827; --line: #e5e7eb; --muted: #6b7280; }
        body { background: #f6f8fb; color: #111827; font-size: 14px; }
        .admin-shell { min-height: 100vh; display: grid; grid-template-columns: 270px 1fr; }
        .sidebar { background: var(--sidebar); color: #d1d5db; padding: 18px 14px; position: sticky; top: 0; height: 100vh; overflow-y: auto; }
        .sidebar a { color: #d1d5db; text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; margin-bottom: 4px; }
        .sidebar a.active, .sidebar a:hover { background: #243244; color: #fff; }
        .brand { font-weight: 700; color: #fff; font-size: 18px; padding: 8px 12px 18px; }
        .content { min-width: 0; }
        .topbar { height: 64px; background: #fff; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; padding: 0 24px; }
        .page { padding: 24px; }
        .cardx { background: #fff; border: 1px solid var(--line); border-radius: 8px; box-shadow: 0 1px 2px rgba(15,23,42,.04); }
        .metric { padding: 18px; min-height: 118px; }
        .metric span { color: var(--muted); font-size: 13px; }
        .metric strong { display: block; font-size: 28px; margin-top: 8px; }
        .table td, .table th { vertical-align: middle; }
        .badge-soft { background: #eef2ff; color: #3730a3; }
        .form-label { font-weight: 600; color: #374151; }
        .preview-img { width: 88px; height: 58px; object-fit: cover; border-radius: 6px; border: 1px solid var(--line); }
        @media (max-width: 991px) { .admin-shell { grid-template-columns: 1fr; } .sidebar { position: static; height: auto; } }
    </style>
    @stack('styles')
</head>
<body>
<div class="admin-shell">
    @include('admin.layouts.sidebar')
    <main class="content">
        @include('admin.layouts.topbar')
        <section class="page">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h4 mb-1">{{ $heading ?? $title ?? 'Dashboard' }}</h1>
                    <div class="text-muted">{{ $subtitle ?? 'Enterprise Android app operations' }}</div>
                </div>
                @yield('actions')
            </div>
            @include('admin.components.alerts')
            @yield('content')
        </section>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function () {
        $('.js-data-table').DataTable({ paging: false, info: false, order: [], responsive: true });
        $('[data-confirm]').on('submit', function (event) {
            if (!confirm($(this).data('confirm'))) event.preventDefault();
        });
        $('[data-image-input]').on('change', function () {
            const target = $($(this).data('image-input'));
            const file = this.files && this.files[0];
            if (file) target.attr('src', URL.createObjectURL(file)).removeClass('d-none');
        });
    });
</script>
@stack('scripts')
</body>
</html>
