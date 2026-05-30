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
        $appId = $request->header('X-App-Id', $request->input('app_id'));
        $apiKey = $request->header('X-App-Key', $request->header('X-Api-Key', $request->input('api_key')));

        $app = AndroidApp::query()
            ->where('app_id', $appId)
            ->where('api_key', $apiKey)
            ->first();

        if (! $app || $app->status !== 'active') {
            return response()->json(['status_code' => 401, 'message' => 'Invalid app credentials'], 401);
        }

        $request->attributes->set('android_app', $app);

        return $next($request);
    }
}
