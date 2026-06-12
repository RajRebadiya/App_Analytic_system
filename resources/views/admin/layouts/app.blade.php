<!doctype html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body class="h-full overflow-hidden">
    <div class="flex h-full overflow-hidden">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            <!-- Topbar -->
            @include('admin.layouts.topbar')

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 md:px-8">
                        <div class="flex flex-col mb-6 md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ $heading ?? $title ?? 'Dashboard' }}</h1>
                                @isset($subtitle)
                                    <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
                                @endisset
                            </div>
                            <div class="flex items-center gap-3">
                                @yield('actions')
                            </div>
                        </div>

                        @include('admin.components.alerts')
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        $(function () {
            // Initialize Lucide icons
            lucide.createIcons();
            
            // Image preview helper
            $('[data-image-input]').on('change', function () {
                const targetId = $(this).data('image-input');
                const target = $(targetId);
                const file = this.files && this.files[0];
                if (file) {
                    const url = URL.createObjectURL(file);
                    target.attr('src', url).removeClass('hidden');
                }
            });

            // Confirm helper
            $(document).on('submit', '[data-confirm]', function (e) {
                if (!confirm($(this).data('confirm'))) {
                    e.preventDefault();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

