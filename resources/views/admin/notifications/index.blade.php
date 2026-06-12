@extends('admin.layouts.app', ['title' => 'Push Notifications', 'heading' => 'Push Notifications', 'subtitle' => 'Compose and send push notifications to your application users via OneSignal.'])

@section('actions')
<a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent shadow-lg shadow-indigo-200 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200">
    <i data-lucide="send" class="w-4 h-4 mr-2"></i>
    Create Notification
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Content</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Visual</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Scheduled</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach($notifications as $notification)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900 truncate max-w-[200px]">{{ $notification->title }}</div>
                            <div class="text-xs text-slate-500 truncate max-w-[200px]">{{ $notification->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($notification->image)
                                <div class="relative w-16 h-10 rounded-lg overflow-hidden border border-slate-200 shadow-sm">
                                    <img class="w-full h-full object-cover" src="{{ str_starts_with($notification->image, 'http') ? $notification->image : asset('storage/'.$notification->image) }}" alt="">
                                </div>
                            @else
                                <div class="w-16 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i data-lucide="image" class="w-4 h-4"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $notification->status === 'success' ? 'bg-emerald-100 text-emerald-700' : ($notification->status === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600') }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $notification->status === 'success' ? 'bg-emerald-500' : ($notification->status === 'failed' ? 'bg-rose-500' : 'bg-slate-400') }}"></span>
                                {{ ucfirst($notification->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-slate-500">
                            {{ $notification->created_at?->format('d M, h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <a href="{{ route('admin.notifications.show', $notification) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors"
                                   title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1.5"></i>
                                    View
                                </a>
                                <form method="POST" action="{{ route('admin.notifications.send', $notification) }}">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all"
                                            title="Resend Notification">
                                        <i data-lucide="rotate-cw" class="w-4 h-4 mr-1.5"></i>
                                        Resend
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.notifications.destroy', $notification) }}" data-confirm="Are you sure you want to delete this notification history?">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                            title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('admin.components.pagination', ['paginator' => $notifications])
</div>
@endsection

