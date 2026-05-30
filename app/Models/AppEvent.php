<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['app_id', 'device_id', 'event_name', 'event_data'])]
class AppEvent extends Model
{
    protected function casts(): array
    {
        return ['event_data' => 'array'];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(AndroidApp::class, 'app_id');
    }
}
