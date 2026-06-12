<?php

namespace App\Services;

class AppProvisioningService
{
    public function createPayload(array $data): array
    {
        $version = '1.0.0';

        return [
            'name' => $data['name'],
            'package_name' => $data['package_name'],
            'current_version' => $version,
            'status' => 'active',
        ];
    }

    public function updatePayload(array $data): array
    {
        return [
            'name' => $data['name'],
            'package_name' => $data['package_name'],
        ];
    }
}
