@php
    $nav = [
        ['Dashboard', 'admin.dashboard', 'admin.dashboard', 'layout-dashboard'],
        ['Apps', 'admin.apps.index', 'admin.apps.*', 'smartphone'],
        ['Install Analytics', 'admin.analytics.installations', 'admin.analytics.installations', 'bar-chart-3'],
        ['DAU / MAU', 'admin.analytics.active-users', 'admin.analytics.active-users', 'users'],
        ['Ad Settings', 'admin.ad-settings.index', 'admin.ad-settings.*', 'settings-2'],
        ['Push Notifications', 'admin.notifications.index', 'admin.notifications.*', 'bell'],
        ['API Logs', 'admin.api-logs.index', 'admin.api-logs.*', 'file-text'],
    ];
@endphp

{{-- Mobile Sidebar Overlay Backdrop --}}
<div id="sidebar-backdrop"
     class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm hidden md:hidden transition-opacity duration-300"
     onclick="closeSidebar()"></div>

{{-- Mobile Sidebar (drawer) + Desktop Sidebar --}}
<div id="sidebar"
     class="fixed inset-y-0 left-0 z-50 flex flex-col w-72 bg-slate-900 transform -translate-x-full transition-transform duration-300 ease-in-out
            md:relative md:translate-x-0 md:flex md:flex-shrink-0 md:w-64">

    <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto sidebar-scroll">
        {{-- Logo + Mobile close button --}}
        <div class="flex items-center flex-shrink-0 px-6 mb-8">
            <div class="flex items-center gap-3 flex-1">
                <div class="bg-indigo-600 p-1.5 rounded-lg shadow-lg shadow-indigo-500/20">
                    <i data-lucide="zap" class="w-6 h-6 text-white fill-white"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">AppAnalytics</span>
            </div>
            {{-- Close button (mobile only) --}}
            <button onclick="closeSidebar()" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <nav class="flex-1 px-3 space-y-1">
            @foreach ($nav as [$label, $route, $match, $icon])
                @php $active = request()->routeIs($match); @endphp
                <a href="{{ route($route) }}"
                   onclick="closeSidebar()"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ $active ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <i data-lucide="{{ $icon }}" class="mr-3 flex-shrink-0 h-5 w-5 transition-colors duration-200 {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <div class="flex flex-shrink-0 p-4 bg-slate-800/50 border-t border-slate-800">
        <a href="{{ route('admin.profile') }}" class="flex-shrink-0 w-full group block">
            <div class="flex items-center">
                <div>
                    <div class="inline-block h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold ring-2 ring-slate-800">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs font-medium text-slate-400 group-hover:text-slate-300 transition-colors">View Profile</p>
                </div>
            </div>
        </a>
    </div>
</div>

<script>
    function openSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
