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
            'package_name' => $this->package_name,
            'current_version' => $this->current_version,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
