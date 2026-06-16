<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['app_id', 'title', 'description', 'image', 'redirect_type', 'redirect_value', 'start_date', 'end_date', 'priority', 'status'])]
class Advertisement extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'priority' => 'integer',
        ];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(AndroidApp::class, 'app_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        $image = str_replace('\\', '/', $this->image);
        $image = ltrim($image, '/');
        $image = preg_replace('#^(storage/|public/)#', '', $image) ?? $image;

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        return route('media.files', ['path' => $image]);
    }
}
