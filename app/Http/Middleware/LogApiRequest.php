<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = microtime(true);
        $response = $next($request);

        ApiLog::query()->create([
            'method' => $request->method(),
            'path' => $request->path(),
            'status_code' => $response->getStatusCode(),
            'response_time_ms' => (int) ((microtime(true) - $startedAt) * 1000),
            'ip_address' => $request->ip(),
            'app_id' => $request->header('app_package_name', $request->header('X-App-Package-Name', $request->input('app_package_name', $request->input('package_name')))),
            'user_id' => $request->user()?->id,
            'request_payload' => $this->safePayload($request->except(['password', 'password_confirmation', 'api_key'])),
            'response_payload' => $this->jsonResponse($response),
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }

    private function safePayload(array $payload): ?array
    {
        return $payload === [] ? null : $payload;
    }

    private function jsonResponse(Response $response): ?array
    {
        $content = $response->getContent();

        if (! $content || strlen($content) > 10000) {
            return null;
        }

        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : null;
    }
}
