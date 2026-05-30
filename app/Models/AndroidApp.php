<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'app_id',
    'package_name',
    'api_key',
    'current_version',
    'min_supported_version',
    'latest_version',
    'force_update',
    'maintenance_mode',
    'status',
])]
class AndroidApp extends Model
{
    protected $table = 'apps';

    protected function casts(): array
    {
        return [
            'force_update' => 'boolean',
            'maintenance_mode' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $app): void {
            $app->api_key ??= Str::random(64);
        });
    }

    protected function apiKey(): Attribute
    {
        return Attribute::make(set: fn (?string $value): ?string => $value ?: Str::random(64));
    }

    public function installations(): HasMany
    {
        return $this->hasMany(AppInstallation::class, 'app_id');
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
