<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppVersionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'app_id' => $this->app_id,
            'latest_version' => $this->latest_version,
            'min_supported_version' => $this->min_supported_version,
            'force_update' => $this->force_update,
            'maintenance_mode' => $this->maintenance_mode,
            'apk_url' => $this->apk_url,
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}
