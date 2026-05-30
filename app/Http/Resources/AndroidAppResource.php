<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AndroidAppResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'app_id' => $this->app_id,
            'package_name' => $this->package_name,
            'api_key' => $this->api_key,
            'current_version' => $this->current_version,
            'min_supported_version' => $this->min_supported_version,
            'latest_version' => $this->latest_version,
            'force_update' => $this->force_update,
            'maintenance_mode' => $this->maintenance_mode,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
