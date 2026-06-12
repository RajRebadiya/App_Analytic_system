<header class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white border-b border-slate-200">
    <div class="flex-1 px-4 flex justify-between">
        <div class="flex-1 flex items-center">
            <div class="w-full max-w-lg lg:max-w-xs">
                <label for="search" class="sr-only">Search</label>
                <div class="relative text-slate-400 focus-within:text-slate-600">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-5 w-5"></i>
                    </div>
                    <input id="search" 
                           class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent sm:text-sm transition-all duration-200" 
                           placeholder="Search analytics..." type="search">
                </div>
            </div>
        </div>
        <div class="ml-4 flex items-center md:ml-6 gap-4">
            <div class="hidden md:block text-sm font-medium text-slate-500">
                Multi Android App Management
            </div>
            
            <div class="h-6 w-px bg-slate-200"></div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-slate-200 shadow-sm text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 hover:text-red-600 hover:border-red-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>

