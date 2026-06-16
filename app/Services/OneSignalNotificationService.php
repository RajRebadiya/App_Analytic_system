<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OneSignalNotificationService
{
    /**
     * @throws ConnectionException
     */
    public function sendToAll(string $title, string $description, string $appId, string $apiKey, ?string $imageUrl = null): array
    {
        if (! $appId || ! $apiKey) {
            throw new RuntimeException('OneSignal credentials are not configured.');
        }

        $payload = [
            'app_id' => $appId,
            'included_segments' => ['All'],
            'headings' => ['en' => $title],
            'contents' => ['en' => $description],
        ];

        if ($imageUrl) {
            $payload['big_picture'] = $imageUrl;
            $payload['chrome_web_image'] = $imageUrl;
        }

        $response = Http::timeout(20)
            ->acceptJson()
            ->withHeaders(['Authorization' => 'Basic '.$apiKey])
            ->post('https://onesignal.com/api/v1/notifications', $payload);

        return [
            'successful' => $response->successful(),
            'status_code' => $response->status(),
            'payload' => $payload,
            'response' => $response->json() ?: ['body' => $response->body()],
        ];
    }
}
