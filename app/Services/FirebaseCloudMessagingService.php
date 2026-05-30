<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;

class FirebaseCloudMessagingService
{
    public function send(string $token, array $payload): array
    {
        $projectId = config('services.fcm.project_id');
        $accessToken = $this->accessToken();

        $response = Http::withToken($accessToken)
            ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $payload['title'],
                        'body' => $payload['description'] ?? '',
                        'image' => $payload['image'] ?? null,
                    ],
                    'data' => array_map('strval', $payload['data'] ?? []),
                ],
            ]);

        return ['successful' => $response->successful(), 'response' => $response->json() ?: ['body' => $response->body()]];
    }

    private function accessToken(): string
    {
        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/firebase.messaging'],
            config('services.fcm.credentials_path'),
        );

        return $credentials->fetchAuthToken()['access_token'];
    }
}
