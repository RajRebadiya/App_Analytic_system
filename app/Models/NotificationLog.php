<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['notification_id', 'device_id', 'fcm_token', 'status', 'response', 'sent_at'])]
class NotificationLog extends Model
{
    protected function casts(): array
    {
        return [
            'response' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(PushNotification::class, 'notification_id');
    }
}
