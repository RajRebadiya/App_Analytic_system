<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['app_id', 'latest_version', 'min_supported_version', 'force_update', 'maintenance_mode', 'apk_url', 'message', 'change_log'])]
class AppVersion extends Model
{
    protected function casts(): array
    {
        return [
            'force_update' => 'boolean',
            'maintenance_mode' => 'boolean',
        ];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(AndroidApp::class, 'app_id');
    }
}
