<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['app_id', 'title', 'description', 'image', 'notification_type', 'send_to', 'redirect_screen', 'redirect_data', 'total_sent', 'total_failed', 'created_by'])]
class PushNotification extends Model
{
    protected $table = 'notifications';

    protected function casts(): array
    {
        return ['redirect_data' => 'array'];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(AndroidApp::class, 'app_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(NotificationLog::class, 'notification_id');
    }
}
