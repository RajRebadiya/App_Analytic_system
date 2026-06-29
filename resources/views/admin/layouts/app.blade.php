<!doctype html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
<body class="h-full">
    <div class="flex h-full overflow-hidden relative">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            <!-- Topbar -->
            @include('admin.layouts.topbar')

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto focus:outline-none">
                <div class="py-4 sm:py-6">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 md:px-8">
                        <div class="flex flex-col mb-4 sm:mb-6 sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="min-w-0">
                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight truncate">{{ $heading ?? $title ?? 'Dashboard' }}</h1>
                                @isset($subtitle)
                                    <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">{{ $subtitle }}</p>
                                @endisset
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0 flex-wrap">
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

            // Flatpickr date inputs
            if (window.flatpickr) {
                document.querySelectorAll('[data-flatpickr-date]').forEach((input) => {
                    flatpickr(input, {
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'M j, Y',
                        allowInput: true,
                    });
                });

                document.querySelectorAll('[data-flatpickr-datetime]').forEach((input) => {
                    flatpickr(input, {
                        enableTime: true,
                        time_24hr: true,
                        dateFormat: 'Y-m-d H:i',
                        altInput: true,
                        altFormat: 'M j, Y H:i',
                        allowInput: true,
                    });
                });

                // Custom Date Range Picker Component Logic
                $('.date-range-picker-container').each(function () {
                    const $container = $(this);
                    const $trigger = $container.find('.daterange-trigger');
                    const $dropdown = $container.find('.daterange-dropdown');
                    const $displayText = $container.find('.daterange-display-text');
                    const $fromInput = $container.find('input[name="from"]');
                    const $toInput = $container.find('input[name="to"]');
                    const $flatpickrInput = $container.find('.daterange-flatpickr');

                    const today = new Date();
                    const formatDate = (d) => {
                        const y = d.getFullYear();
                        const m = String(d.getMonth() + 1).padStart(2, '0');
                        const day = String(d.getDate()).padStart(2, '0');
                        return `${y}-${m}-${day}`;
                    };
                    const formatDisplay = (dateStr) => {
                        if (!dateStr) return '';
                        const parts = dateStr.split('-');
                        if (parts.length === 3) {
                            return `${parts[2]}-${parts[1]}-${parts[0]}`;
                        }
                        return dateStr;
                    };

                    const todayStr = formatDate(today);
                    const getRelativeDateStr = (offset) => {
                        const d = new Date();
                        d.setDate(d.getDate() + offset);
                        return formatDate(d);
                    };

                    const yesterdayStr = getRelativeDateStr(-1);
                    const last7Str = getRelativeDateStr(-6);
                    const last30Str = getRelativeDateStr(-29);

                    const thisMonthStart = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
                    const lastMonthStart = formatDate(new Date(today.getFullYear(), today.getMonth() - 1, 1));
                    const lastMonthEnd = formatDate(new Date(today.getFullYear(), today.getMonth(), 0));

                    let activeRange = 'custom';
                    const fromVal = $fromInput.val();
                    const toVal = $toInput.val();

                    if (fromVal && toVal) {
                        if (fromVal === todayStr && toVal === todayStr) {
                            activeRange = 'today';
                        } else if (fromVal === yesterdayStr && toVal === yesterdayStr) {
                            activeRange = 'yesterday';
                        } else if (fromVal === last7Str && toVal === todayStr) {
                            activeRange = 'last_7';
                        } else if (fromVal === last30Str && toVal === todayStr) {
                            activeRange = 'last_30';
                        } else if (fromVal === thisMonthStart && toVal === todayStr) {
                            activeRange = 'this_month';
                        } else if (fromVal === lastMonthStart && toVal === lastMonthEnd) {
                            activeRange = 'last_month';
                        }
                        $displayText.text(`${formatDisplay(fromVal)} - ${formatDisplay(toVal)}`).removeClass('text-slate-500').addClass('text-slate-800 font-semibold');
                    } else {
                        $displayText.text('Select Date Range').addClass('text-slate-500').removeClass('text-slate-800 font-semibold');
                    }

                    // Style the active item in the dropdown
                    $dropdown.find('.daterange-item').removeClass('bg-blue-600 text-white font-semibold').addClass('text-slate-700 hover:bg-slate-50');
                    const $activeItem = $dropdown.find(`[data-range="${activeRange}"]`);
                    if ($activeItem.length) {
                        $activeItem.addClass('bg-blue-600 text-white font-semibold').removeClass('text-slate-700 hover:bg-slate-50');
                    }

                    // Toggle dropdown opening/closing
                    $trigger.on('click', function (e) {
                        e.stopPropagation();
                        const isHidden = $dropdown.hasClass('hidden');
                        if (isHidden) {
                            // Close other date picker dropdowns if any
                            $('.daterange-dropdown').addClass('hidden');
                            $('.daterange-trigger').removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
                            $('.daterange-trigger').find('[data-lucide="chevron-down"]').removeClass('rotate-180');

                            $dropdown.removeClass('hidden');
                            $trigger.addClass('border-blue-500 ring-2 ring-blue-100').removeClass('border-slate-200');
                            $trigger.find('[data-lucide="chevron-down"]').addClass('rotate-180');
                        } else {
                            $dropdown.addClass('hidden');
                            $trigger.removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
                            $trigger.find('[data-lucide="chevron-down"]').removeClass('rotate-180');
                        }
                    });

                    // Hide dropdown when clicking outside
                    $(document).on('click', function (e) {
                        if (!$container.is(e.target) && $container.has(e.target).length === 0) {
                            $dropdown.addClass('hidden');
                            $trigger.removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
                            $trigger.find('[data-lucide="chevron-down"]').removeClass('rotate-180');
                        }
                    });

                    // Handle preset selection
                    $dropdown.find('.daterange-item').on('click', function (e) {
                        const range = $(this).data('range');
                        if (range === 'custom') {
                            e.stopPropagation();
                            $dropdown.addClass('hidden');
                            $trigger.removeClass('border-blue-500 ring-2 ring-blue-100').addClass('border-slate-200');
                            $trigger.find('[data-lucide="chevron-down"]').removeClass('rotate-180');
                            fp.open();
                            return;
                        }

                        let from = '';
                        let to = '';
                        switch (range) {
                            case 'today':
                                from = todayStr;
                                to = todayStr;
                                break;
                            case 'yesterday':
                                from = yesterdayStr;
                                to = yesterdayStr;
                                break;
                            case 'last_7':
                                from = last7Str;
                                to = todayStr;
                                break;
                            case 'last_30':
                                from = last30Str;
                                to = todayStr;
                                break;
                            case 'this_month':
                                from = thisMonthStart;
                                to = todayStr;
                                break;
                            case 'last_month':
                                from = lastMonthStart;
                                to = lastMonthEnd;
                                break;
                        }

                        $fromInput.val(from);
                        $toInput.val(to);
                        $container.closest('form').submit();
                    });

                    // Initialize flatpickr internally on the hidden input for Custom Range selection
                    const fp = flatpickr($flatpickrInput[0], {
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        onClose: function (selectedDates, dateStr, instance) {
                            if (selectedDates.length === 2) {
                                const from = formatDate(selectedDates[0]);
                                const to = formatDate(selectedDates[1]);
                                $fromInput.val(from);
                                $toInput.val(to);
                                $container.closest('form').submit();
                            }
                        }
                    });
                });
            }
            
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

            // Pagination Per Page change handler
            $(document).on('change', '.per-page-select', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', this.value);
                url.searchParams.set('page', 1);
                window.location.href = url.href;
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
