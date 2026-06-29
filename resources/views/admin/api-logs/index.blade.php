@extends('admin.layouts.app', ['title' => 'API Logs', 'heading' => 'API Infrastructure Monitoring', 'subtitle' => 'Real-time monitoring of all incoming API requests and their response statuses.'])

@section('content')
<!-- Filter Bar -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-8">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
        <div class="sm:col-span-2 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="search" class="w-5 h-5"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm" 
                   placeholder="Search by endpoint path or IP address...">
        </div>
        <div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                </div>
                <input type="text" name="status_code" value="{{ request('status_code') }}" 
                       class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm" 
                       placeholder="Status Code (e.g. 200)">
            </div>
        </div>
        <div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="activity" class="w-4 h-4"></i>
                </div>
                <input type="text" name="action" value="{{ request('action') }}" 
                       class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm" 
                       placeholder="Action (e.g. apps/install)">
            </div>
        </div>
        <div>
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-2.5 border border-slate-200 text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-all duration-200">
                <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                Update Results
            </button>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Request</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Latency</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Application</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">IP Address</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Timestamp</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach($logs as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200 font-mono">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $log->method === 'GET' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $log->method }}
                                </span>
                                <span class="text-xs font-bold text-slate-700 truncate max-w-[200px]" title="{{ $log->path }}">{{ $log->path }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                {{ $log->action ?: 'n/a' }}
                            </code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $log->status_code >= 400 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $log->status_code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-xs font-bold {{ $log->response_time_ms > 500 ? 'text-amber-600' : 'text-slate-500' }}">
                                <i data-lucide="clock" class="w-3 h-3 mr-1.5 opacity-50"></i>
                                {{ $log->response_time_ms }}<span class="text-[10px] ml-0.5 font-normal">ms</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $log->app_id ?: 'SYS' }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                            {{ $log->ip_address }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-[10px] text-slate-400 font-bold uppercase">
                            {{ $log->created_at->format('M d, H:i:s') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('admin.components.pagination', ['paginator' => $logs])
</div>
@endsection
