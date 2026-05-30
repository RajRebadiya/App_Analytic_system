<?php

namespace App\Services;

use App\Models\AndroidApp;
use Illuminate\Support\Str;

class AppProvisioningService
{
    public function createPayload(array $data): array
    {
        $version = $data['version'];
        $identity = $this->identity($data['name']);

        return [
            'name' => $data['name'],
            'app_id' => $identity['app_id'],
            'package_name' => $identity['package_name'],
            'current_version' => $version,
            'min_supported_version' => $version,
            'latest_version' => $version,
            'force_update' => false,
            'maintenance_mode' => false,
            'status' => 'active',
        ];
    }

    public function updatePayload(array $data): array
    {
        return [
            'name' => $data['name'],
            'current_version' => $data['version'],
            'min_supported_version' => $data['version'],
            'latest_version' => $data['version'],
        ];
    }

    private function identity(string $name): array
    {
        $slug = Str::slug($name) ?: 'app';

        do {
            $suffix = Str::lower(Str::random(8));
            $appId = "{$slug}-{$suffix}";
            $packageName = 'com.appanalytics.'.Str::replace('-', '', $slug).'.'.$suffix;
        } while (
            AndroidApp::query()->where('app_id', $appId)->orWhere('package_name', $packageName)->exists()
        );

        return [
            'app_id' => $appId,
            'package_name' => $packageName,
        ];
    }
}
