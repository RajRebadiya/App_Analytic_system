@if ($paginator && $paginator->total() > 0)
    <div class="mt-8 px-6 py-4 bg-slate-50 border-t border-slate-100 rounded-b-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Per Page Dropdown -->
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <span>Show</span>
            <select class="per-page-select block w-20 px-2.5 py-1.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all text-xs font-medium text-slate-700 cursor-pointer shadow-sm">
                @foreach([10, 25, 50, 100, 500] as $size)
                    <option value="{{ $size }}" @selected(request('per_page', 10) == $size)>{{ $size }}</option>
                @endforeach
            </select>
            <span>entries</span>
        </div>

        <!-- Pagination Links -->
        <div class="flex-1 flex justify-end">
            {{ $paginator->links() }}
        </div>
    </div>
@endif
