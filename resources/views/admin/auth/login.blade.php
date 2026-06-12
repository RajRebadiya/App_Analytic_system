<!doctype html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-xl shadow-indigo-200">
                    <i data-lucide="zap" class="w-10 h-10 text-white fill-white"></i>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900 tracking-tight">
                Admin Control Panel
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500 font-medium">
                Log in to manage your ecosystem
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
            <div class="bg-white py-8 px-6 shadow-2xl shadow-slate-200/50 rounded-3xl border border-slate-100">
                @include('admin.components.alerts')

                <form class="space-y-6" method="POST" action="{{ route('admin.login.store') }}">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700">Email Address</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="mail" class="h-5 w-5"></i>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                   class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm shadow-sm"
                                   placeholder="admin@example.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="lock" class="h-5 w-5"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                   class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all duration-200 sm:text-sm shadow-sm"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer">
                            <label for="remember" class="ml-2 block text-sm font-bold text-slate-600 cursor-pointer">
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="{{ route('admin.password.request') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                            Sign in to Dashboard
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-8 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500 font-medium">
                        New to the platform?
                        <a href="{{ route('admin.register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 ml-1 transition-colors">
                            Create Account
                        </a>
                    </p>
                </div>
            </div>
            
            <p class="mt-10 text-center text-xs text-slate-400 font-medium uppercase tracking-widest">
                &copy; {{ date('Y') }} {{ config('app.name') }} Enterprise
            </p>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>

