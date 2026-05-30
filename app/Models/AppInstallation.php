<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['app_id', 'device_id', 'device_name', 'device_brand', 'android_version', 'app_version', 'ip_address', 'installed_at', 'last_active_at'])]
class AppInstallation extends Model
{
    protected function casts(): array
    {
        return [
            'installed_at' => 'datetime',
            'last_active_at' => 'datetime',
        ];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(AndroidApp::class, 'app_id');
    }
}
