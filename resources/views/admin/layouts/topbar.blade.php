<header class="sticky top-0 z-30 flex-shrink-0 flex h-16 bg-white border-b border-slate-200">
    <div class="flex-1 px-4 flex items-center justify-between">
        {{-- Left: Hamburger (mobile) + Search --}}
        <div class="flex items-center gap-3 flex-1">
            {{-- Hamburger button - mobile only --}}
            <button onclick="openSidebar()"
                    class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all duration-200 flex-shrink-0"
                    aria-label="Open navigation menu">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            {{-- Search box --}}
            <div class="flex-1 max-w-xs sm:max-w-sm lg:max-w-xs">
                <label for="search" class="sr-only">Search</label>
                <div class="relative text-slate-400 focus-within:text-slate-600">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-4 w-4"></i>
                    </div>
                    <input id="search"
                           class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent text-sm transition-all duration-200"
                           placeholder="Search..." type="search">
                </div>
            </div>
        </div>

        {{-- Right: Label + Logout --}}
        <div class="flex items-center gap-3 ml-3">
            <div class="hidden lg:block text-sm font-medium text-slate-500 whitespace-nowrap">
                Multi Android App Management
            </div>

            <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                {{-- Full logout button on tablet+ --}}
                <button type="submit"
                        class="hidden sm:inline-flex items-center px-3 py-1.5 border border-slate-200 shadow-sm text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 hover:text-red-600 hover:border-red-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i data-lucide="log-out" class="w-4 h-4 sm:mr-2"></i>
                    <span class="hidden sm:inline">Logout</span>
                </button>
                {{-- Icon-only logout on mobile --}}
                <button type="submit"
                        class="sm:hidden p-2 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>
</header>
