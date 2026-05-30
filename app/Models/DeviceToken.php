<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['app_id', 'device_id', 'fcm_token', 'is_active'])]
class DeviceToken extends Model
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(AndroidApp::class, 'app_id');
    }
}
