@if (session('status'))
    <div class="mb-6 flex items-center p-4 rounded-2xl bg-emerald-50 border border-emerald-100 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="flex-shrink-0 bg-emerald-500 p-1.5 rounded-lg shadow-lg shadow-emerald-500/20">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-white"></i>
        </div>
        <div class="ml-3 text-sm font-bold text-emerald-800">
            {{ session('status') }}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="flex items-start">
            <div class="flex-shrink-0 bg-rose-500 p-1.5 rounded-lg shadow-lg shadow-rose-500/20">
                <i data-lucide="alert-circle" class="w-5 h-5 text-white"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-rose-800 mb-1">Please review the form.</h3>
                <ul class="text-xs font-medium text-rose-600 list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

