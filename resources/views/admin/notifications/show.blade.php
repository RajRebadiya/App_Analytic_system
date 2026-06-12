@extends('admin.layouts.app', ['title' => 'Notification Details', 'heading' => 'Notification Intelligence', 'subtitle' => 'Detailed insights into the delivery status and content of your push notification.'])

@section('actions')
<a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-4 py-2.5 border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-all duration-200">
    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
    Back to History
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="space-y-8">
        <!-- Main Details -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Message Content</h3>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Title</span>
                    <h2 class="text-xl font-bold text-slate-900 leading-tight">{{ $notification->title }}</h2>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Description Body</span>
                    <p class="text-slate-600 font-medium leading-relaxed">{{ $notification->description }}</p>
                </div>
                <div class="flex flex-wrap gap-8 pt-4 border-t border-slate-100">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Delivery Status</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $notification->status === 'success' ? 'bg-emerald-100 text-emerald-700' : ($notification->status === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600') }}">
                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $notification->status === 'success' ? 'bg-emerald-500' : ($notification->status === 'failed' ? 'bg-rose-500' : 'bg-slate-400') }}"></span>
                            {{ ucfirst($notification->status ?? 'pending') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Created At</span>
                        <div class="text-xs font-bold text-slate-700">{{ $notification->created_at?->format('d M Y, h:i A') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                <form method="POST" action="{{ route('admin.notifications.send', $notification) }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-lg shadow-emerald-200 text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 transition-all duration-200">
                        <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                        Resend to All Users
                    </button>
                </form>
            </div>
        </div>

        @if($notification->image)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Visual Assets</h3>
                </div>
                <div class="p-6">
                    <img class="w-full h-auto rounded-xl border border-slate-200 shadow-sm" src="{{ str_starts_with($notification->image, 'http') ? $notification->image : asset('storage/'.$notification->image) }}" alt="">
                </div>
            </div>
        @endif
    </div>

    <div class="space-y-8">
        <!-- Infrastructure Response -->
        <div class="bg-slate-900 rounded-2xl shadow-xl overflow-hidden h-full flex flex-col">
            <div class="p-4 border-b border-slate-800 bg-slate-900 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                </div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Provider Response JSON</span>
            </div>
            <div class="p-6 overflow-auto flex-1 font-mono text-xs leading-relaxed custom-scrollbar text-emerald-400">
                @if($notification->onesignal_response)
                    <pre>{{ json_encode($notification->onesignal_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                @else
                    <div class="flex flex-col items-center justify-center h-full text-slate-600">
                        <i data-lucide="terminal" class="w-8 h-8 mb-2"></i>
                        <p>No response data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

