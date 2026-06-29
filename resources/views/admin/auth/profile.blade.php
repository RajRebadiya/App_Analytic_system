@extends('admin.layouts.app', ['title' => 'Profile', 'heading' => 'Account Settings', 'subtitle' => 'Update your personal information and manage your account credentials.'])

@section('content')
<div class="max-w-4xl w-full">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Personal Information</h3>
        </div>
        
        <form method="POST" action="{{ route('admin.profile.update') }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Display Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm font-medium">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm font-medium">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.password.change') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                    <i data-lucide="lock" class="w-4 h-4 mr-2"></i>
                    Change Password
                </a>
                <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent shadow-lg shadow-indigo-200 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

