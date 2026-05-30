<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(string $message = 'Success', mixed $data = [], int $code = 200): JsonResponse
    {
        return response()->json(['status_code' => $code, 'message' => $message, 'data' => $data], $code);
    }

    protected function error(string $message, int $code = 500, array $errors = []): JsonResponse
    {
        $payload = ['status_code' => $code, 'message' => $message];

        if ($errors !== []) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }
}
