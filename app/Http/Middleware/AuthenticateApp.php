<?php

namespace App\Http\Middleware;

use App\Models\AndroidApp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApp
{
    public function handle(Request $request, Closure $next): Response
    {
        $packageName = $request->header(
            'app_package_name',
            $request->header('X-App-Package-Name', $request->input('app_package_name', $request->input('package_name')))
        );

        $app = AndroidApp::query()
            ->where('package_name', $packageName)
            ->first();

        if (! $app || $app->status !== 'active') {
            return response()->json(['status_code' => 401, 'message' => 'Invalid app package name'], 401);
        }

        $request->attributes->set('android_app', $app);

        return $next($request);
    }
}
