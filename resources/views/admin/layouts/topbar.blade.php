<header class="topbar">
    <div class="text-muted">Multi Android App Management</div>
    <div class="d-flex align-items-center gap-3">
        <a class="text-decoration-none" href="{{ route('admin.profile') }}">{{ auth()->user()->name }}</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-secondary">Logout</button>
        </form>
    </div>
</header>
