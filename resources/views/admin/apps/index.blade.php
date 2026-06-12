@extends('admin.layouts.app', ['title' => 'Apps', 'heading' => 'Application Management', 'subtitle' => 'Manage your Android applications, monitor their status and package details.'])

@section('actions')
<div class="flex flex-wrap gap-3">
    <a href="{{ route('admin.apps.export') }}" class="inline-flex items-center px-4 py-2.5 border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-all duration-200">
        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
        Export CSV
    </a>
    <a href="{{ route('admin.apps.create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent shadow-lg shadow-indigo-200 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
        Add New App
    </a>
</div>
@endsection

@section('content')
<!-- Search & Filter -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-8">
    <form method="GET" class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
            <i data-lucide="search" class="w-5 h-5"></i>
        </div>
        <input type="text" name="search" value="{{ request('search') }}" 
               class="block w-full pl-10 pr-32 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm" 
               placeholder="Search by app name or package identifier...">
        <div class="absolute inset-y-0 right-0 flex items-center p-1.5">
            <button type="submit" class="h-full px-4 text-sm font-bold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors">
                Search
            </button>
        </div>
    </form>
</div>

<!-- Apps Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Application</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Package Identifier</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Current Version</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach ($apps as $app)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                                    <i data-lucide="smartphone" class="w-5 h-5"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-900">{{ $app->name }}</div>
                                    <div class="text-xs text-slate-500">ID: {{ $app->app_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">{{ $app->package_name }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">
                            {{ $app->current_version }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $app->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $app->status === 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <a href="{{ route('admin.apps.edit', $app) }}" 
                                   class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                   title="Edit App">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.apps.status', [$app, $app->status === 'active' ? 'inactive' : 'active']) }}">
                                    @csrf 
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                            title="{{ $app->status === 'active' ? 'Suspend' : 'Activate' }}">
                                        <i data-lucide="{{ $app->status === 'active' ? 'pause-circle' : 'play-circle' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('admin.components.pagination', ['paginator' => $apps])
</div>
@endsection

