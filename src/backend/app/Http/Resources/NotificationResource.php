<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'sender_id' => $this->sender_id,
            'recipient' => $this->recipient->full_name,
            'data' => $this->notifiable,
            'type' => $this->type,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
        ];
    }
}
