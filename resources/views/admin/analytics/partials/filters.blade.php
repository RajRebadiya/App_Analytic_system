<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Select Application</label>
            <div class="relative">
                <select name="app_id" class="block w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm appearance-none font-medium text-slate-700">
                    <option value="">All Applications</option>
                    @foreach($apps as $app)
                        <option value="{{ $app->id }}" @selected(request('app_id') == $app->id)>{{ $app->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Country Code</label>
            <input name="country_code" type="text" value="{{ request('country_code') }}" placeholder="IN, US"
                   class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm font-medium text-slate-700 uppercase">
        </div>
        <div class="md:col-span-2 relative">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Date Range</label>
            <div class="relative date-range-picker-container" data-from="{{ request('from') }}" data-to="{{ request('to') }}">
                <input type="hidden" name="from" value="{{ request('from') }}">
                <input type="hidden" name="to" value="{{ request('to') }}">
                <button type="button" class="daterange-trigger flex items-center justify-between w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 text-sm font-semibold text-slate-700 shadow-sm cursor-pointer">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                        <span class="daterange-display-text text-slate-600 font-medium">Select Date Range</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-200"></i>
                </button>
                
                <div class="daterange-dropdown hidden absolute left-0 right-0 z-50 mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl">
                    <div class="absolute -top-1.5 left-6 w-3 h-3 bg-white border-t border-l border-slate-200 rotate-45"></div>
                    <div class="py-2 relative bg-white rounded-2xl overflow-hidden shadow-inner">
                        <button type="button" data-range="today" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Today</button>
                        <button type="button" data-range="yesterday" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Yesterday</button>
                        <button type="button" data-range="last_7" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Last 7 Days</button>
                        <button type="button" data-range="last_30" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Last 30 Days</button>
                        <button type="button" data-range="this_month" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">This Month</button>
                        <button type="button" data-range="last_month" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">Last Month</button>
                        <button type="button" data-range="custom" class="daterange-item block w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium border-t border-slate-100">Custom Range</button>
                    </div>
                </div>
                <input type="text" class="daterange-flatpickr absolute opacity-0 pointer-events-none inset-0 w-full h-full">
            </div>
        </div>
        <div>
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-100 transition-all duration-200">
                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                Refresh Data
            </button>
        </div>
    </form>
</div>
