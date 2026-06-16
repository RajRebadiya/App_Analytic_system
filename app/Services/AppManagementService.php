<?php

namespace App\Services;

use App\Models\AndroidApp;
use App\Models\AppInstallEvent;
use App\Models\AppEvent;
use App\Models\AppInstallation;
use App\Models\DeviceToken;
use Illuminate\Support\Carbon;

class AppManagementService
{
    public function trackInstall(AndroidApp $app, array $data, ?string $ipAddress): AppInstallation
    {
        $installation = AppInstallation::query()->updateOrCreate(
            ['app_id' => $app->id, 'device_id' => $data['device_id']],
            [
                'device_name' => $data['device_name'] ?? null,
                'device_brand' => $data['device_brand'] ?? null,
                'android_version' => $data['android_version'] ?? null,
                'country_code' => isset($data['country_code']) && $data['country_code'] !== ''
                    ? strtoupper($data['country_code'])
                    : null,
                'app_version' => $data['app_version'],
                'ip_address' => $ipAddress,
                'installed_at' => Carbon::now(),
                'last_active_at' => Carbon::now(),
            ],
        );

        AppInstallEvent::query()->create([
            'app_id' => $app->id,
            'device_id' => $data['device_id'],
            'device_name' => $data['device_name'] ?? null,
            'device_brand' => $data['device_brand'] ?? null,
            'android_version' => $data['android_version'] ?? null,
            'country_code' => isset($data['country_code']) && $data['country_code'] !== ''
                ? strtoupper($data['country_code'])
                : null,
            'app_version' => $data['app_version'],
            'ip_address' => $ipAddress,
        ]);

        return $installation;
    }

    public function heartbeat(AndroidApp $app, array $data): AppInstallation
    {
        return AppInstallation::query()->updateOrCreate(
            ['app_id' => $app->id, 'device_id' => $data['device_id']],
            ['app_version' => $data['app_version'] ?? $app->current_version, 'last_active_at' => Carbon::now()],
        );
    }

    public function saveToken(AndroidApp $app, array $data): DeviceToken
    {
        return DeviceToken::query()->updateOrCreate(
            ['app_id' => $app->id, 'device_id' => $data['device_id']],
            ['fcm_token' => $data['fcm_token'], 'is_active' => $data['is_active'] ?? true],
        );
    }

    public function versionCheck(AndroidApp $app, string $currentVersion): array
    {
        $version = $app->versions()->latest()->first();
        $latest = $version?->latest_version ?? $app->current_version ?? '1.0.0';
        $minimum = $version?->min_supported_version ?? $app->current_version ?? '1.0.0';
        $maintenance = (bool) ($version?->maintenance_mode ?? false);
        $force = $maintenance || version_compare($currentVersion, $minimum, '<') || (bool) ($version?->force_update ?? false);

        return [
            'maintenance_mode' => $maintenance,
            'force_update' => $force,
            'optional_update' => ! $force && version_compare($latest, $currentVersion, '>'),
            'latest_version' => $latest,
            'min_supported_version' => $minimum,
            'apk_url' => $version?->apk_url,
            'message' => $version?->message,
        ];
    }

    public function storeEvent(AndroidApp $app, array $data): AppEvent
    {
        return AppEvent::query()->create(['app_id' => $app->id] + $data);
    }
}
