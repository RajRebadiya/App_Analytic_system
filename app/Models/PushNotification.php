<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['app_id', 'title', 'description', 'image', 'onesignal_response', 'status', 'is_active', 'notification_type', 'send_to', 'redirect_screen', 'redirect_data', 'total_sent', 'total_failed', 'created_by', 'scheduled_at', 'schedule_frequency'])]
class PushNotification extends Model
{
    protected $table = 'notifications';

    protected function casts(): array
    {
        return [
            'onesignal_response' => 'array',
            'redirect_data' => 'array',
            'scheduled_at' => 'datetime',
        ];
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

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        $image = str_replace('\\', '/', $this->image);
        $image = ltrim($image, '/');
        $image = preg_replace('#^(storage/|public/)#', '', $image) ?? $image;
        $image = preg_replace('#^notifications/#', 'notification/', $image) ?? $image;

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        return asset($image);
    }
}
