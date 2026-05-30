<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'notification_type' => $this->notification_type,
            'send_to' => $this->send_to,
            'total_sent' => $this->total_sent,
            'total_failed' => $this->total_failed,
            'created_at' => $this->created_at,
        ];
    }
}
