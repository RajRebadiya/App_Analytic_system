@if ($paginator->hasPages())
    <div class="mt-8 px-6 py-4 bg-slate-50 border-t border-slate-100 rounded-b-2xl">
        {{ $paginator->links() }}
    </div>
@endif

