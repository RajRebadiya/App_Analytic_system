@extends('admin.layouts.app', ['title' => 'Change Password', 'heading' => 'Update Security', 'subtitle' => 'Ensure your account remains secure by regularly updating your password.'])

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="p-2 bg-white rounded-xl shadow-sm text-amber-600 border border-slate-100">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Password Credentials</h3>
        </div>
        
        <form method="POST" action="{{ route('admin.password.update') }}" class="p-8 space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-8 max-w-xl">
                <div>
                    <label for="current_password" class="block text-sm font-bold text-slate-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required
                           class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm shadow-sm"
                           placeholder="••••••••">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-slate-100">
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">New Password</label>
                        <input type="password" name="password" id="password" required
                               class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm shadow-sm"
                               placeholder="••••••••">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm shadow-sm"
                               placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.profile') }}" class="px-6 py-3 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                    Back to Profile
                </a>
                <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent shadow-lg shadow-indigo-200 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    <i data-lucide="key" class="w-4 h-4 mr-2"></i>
                    Update Security
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

