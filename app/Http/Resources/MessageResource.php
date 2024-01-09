<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
    "id" => $this->id,
            "sender_id" => $this->sender_id,
            "receiver_id" => $this->receiver_id,
            "message" => nl2br($this->message),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "is_read" => $this->is_read,
            "is_hidden" => $this->is_hidden,
            "can_be_hidden" => 0 === $this->is_hidden && $this->receiver_id === Auth::id(),
        ];
    }
}
