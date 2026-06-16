<?php

namespace App\Services;

class AppProvisioningService
{
    public function createPayload(array $data): array
    {
        return [
            'name' => $data['name'],
            'package_name' => $data['package_name'],
            'current_version' => $data['current_version'] ?? '1.0.0',
            'onesignal_app_id' => $data['onesignal_app_id'] ?? null,
            'onesignal_api_key' => $data['onesignal_api_key'] ?? null,
            'status' => 'active',
        ];
    }

    public function updatePayload(array $data): array
    {
        return [
            'name' => $data['name'],
            'package_name' => $data['package_name'],
            'current_version' => $data['current_version'] ?? '1.0.0',
            'onesignal_app_id' => $data['onesignal_app_id'] ?? null,
            'onesignal_api_key' => $data['onesignal_api_key'] ?? null,
        ];
    }
}
