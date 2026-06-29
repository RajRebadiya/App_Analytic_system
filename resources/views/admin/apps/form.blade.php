@extends('admin.layouts.app', ['title' => $app->exists ? 'Edit App' : 'Add App', 'heading' => $app->exists ? 'Edit Application' : 'Register New Application', 'subtitle' => 'Provide the necessary details to manage your Android application on the platform.'])

@section('content')
<div class="max-w-4xl w-full">
    <form action="{{ $app->exists ? route('admin.apps.update', $app) : route('admin.apps.store') }}" method="POST" class="space-y-8">
        @csrf
        @if($app->exists) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Application Details</h3>
            </div>
            
            <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">App Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $app->name) }}" required
                           class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm"
                           placeholder="e.g. My Awesome News App">
                    <p class="mt-2 text-xs text-slate-500 font-medium">Internal display name for the application.</p>
                </div>

                <div>
                    <label for="package_name" class="block text-sm font-bold text-slate-700 mb-2">Package Identifier</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="package" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="package_name" id="package_name" value="{{ old('package_name', $app->package_name) }}" required
                               class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm"
                               placeholder="com.company.appname">
                    </div>
                    <p class="mt-2 text-xs text-slate-500 font-medium">Unique Android package name (e.g., com.example.app).</p>
                </div>

                <div>
                    <label for="current_version" class="block text-sm font-bold text-slate-700 mb-2">Current Version</label>
                    <input type="text" name="current_version" id="current_version" value="{{ old('current_version', $app->current_version ?: '1.0.0') }}" required
                           class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm"
                           placeholder="1.0.0">
                    <p class="mt-2 text-xs text-slate-500 font-medium">Version shown to the app and used for update checks.</p>
                </div>
            </div>

            <div class="px-4 sm:px-6 pb-6 grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8">
                <div>
                    <label for="onesignal_app_id" class="block text-sm font-bold text-slate-700 mb-2">OneSignal App ID</label>
                    <input type="text" name="onesignal_app_id" id="onesignal_app_id" value="{{ old('onesignal_app_id', $app->onesignal_app_id) }}"
                           class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm"
                           placeholder="1eeedc9d-d959-47ed-b7f8-c138f4ba02e7">
                    <p class="mt-2 text-xs text-slate-500 font-medium">Save it once here. Notifications will reuse this app-level credential.</p>
                </div>

                <div>
                    <label for="onesignal_api_key" class="block text-sm font-bold text-slate-700 mb-2">OneSignal API Key</label>
                    <input type="password" name="onesignal_api_key" id="onesignal_api_key" value="{{ old('onesignal_api_key', $app->onesignal_api_key) }}"
                           class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm"
                           placeholder="os_v2_app_...">
                    <p class="mt-2 text-xs text-slate-500 font-medium">Stored against this app and used automatically for sending notifications.</p>
                </div>
            </div>

            <div class="px-6 py-4 bg-indigo-50/50 border-t border-indigo-100">
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-indigo-600">
                        <i data-lucide="info" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-bold text-indigo-800">System Defaults</p>
                        <p class="text-xs text-indigo-700 font-medium mt-0.5">OneSignal credentials are saved once at the app level and reused for future notifications.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.apps.index') }}" class="px-6 py-3 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                Cancel & Return
            </a>
            <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent shadow-lg shadow-indigo-200 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                {{ $app->exists ? 'Update Application' : 'Create Application' }}
            </button>
        </div>
    </form>
</div>
@endsection
