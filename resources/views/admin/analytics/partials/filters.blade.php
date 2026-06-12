<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
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
            <label class="block text-sm font-semibold text-slate-700 mb-2">Start Date</label>
            <input name="from" type="date" value="{{ request('from') }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm font-medium text-slate-700">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">End Date</label>
            <input name="to" type="date" value="{{ request('to') }}" class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm font-medium text-slate-700">
        </div>
        <div>
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-100 transition-all duration-200">
                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                Refresh Data
            </button>
        </div>
    </form>
</div>

