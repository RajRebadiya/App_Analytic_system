<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdNetworkSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return ['id' => $this->id] + $this->toAndroidPayload();
    }
}
