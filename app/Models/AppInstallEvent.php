<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['app_id', 'device_id', 'device_name', 'device_brand', 'android_version', 'country_code', 'app_version', 'ip_address'])]
class AppInstallEvent extends Model
{
    protected $table = 'app_install_events';

    public function app(): BelongsTo
    {
        return $this->belongsTo(AndroidApp::class, 'app_id');
    }
}
