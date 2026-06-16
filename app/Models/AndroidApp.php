<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'name',
    'package_name',
    'current_version',
    'onesignal_app_id',
    'onesignal_api_key',
    'status',
])]
class AndroidApp extends Model
{
    protected $table = 'apps';

    public function installations(): HasMany
    {
        return $this->hasMany(AppInstallation::class, 'app_id');
    }

    public function installEvents(): HasMany
    {
        return $this->hasMany(AppInstallEvent::class, 'app_id');
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class, 'app_id');
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class, 'app_id');
    }

    public function adNetworkSetting(): HasOne
    {
        return $this->hasOne(AdNetworkSetting::class, 'app_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(PushNotification::class, 'app_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(AppVersion::class, 'app_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(AppEvent::class, 'app_id');
    }
}
