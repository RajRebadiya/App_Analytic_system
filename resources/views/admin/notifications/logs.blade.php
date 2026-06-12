@extends('admin.layouts.app', ['title' => 'Delivery Logs', 'heading' => 'Notification Delivery Logs', 'subtitle' => 'Detailed per-device transmission logs for the selected notification.'])

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8 flex items-center justify-between">
    <div>
        <h4 class="text-lg font-bold text-slate-900">{{ $notification->title }}</h4>
        <p class="text-sm text-slate-500 font-medium">Broadcast Statistics</p>
    </div>
    <div class="flex gap-6">
        <div class="text-center">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Delivered</div>
            <div class="text-xl font-black text-emerald-600 tracking-tight">{{ number_format($notification->total_sent) }}</div>
        </div>
        <div class="text-center">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Failed</div>
            <div class="text-xl font-black text-rose-600 tracking-tight">{{ number_format($notification->total_failed) }}</div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 font-mono text-xs">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left font-bold text-slate-500 uppercase tracking-wider">Device Identifier</th>
                    <th scope="col" class="px-6 py-4 text-left font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-left font-bold text-slate-500 uppercase tracking-wider">Sent At</th>
                    <th scope="col" class="px-6 py-4 text-left font-bold text-slate-500 uppercase tracking-wider">Infrastructure Response</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach($logs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-bold">
                            {{ $log->device_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $log->status === 'sent' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $log->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                            {{ $log->sent_at }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs truncate text-[10px] text-slate-400 italic" title="{{ json_encode($log->response) }}">
                                {{ json_encode($log->response) }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('admin.components.pagination', ['paginator' => $logs])
</div>
@endsection

