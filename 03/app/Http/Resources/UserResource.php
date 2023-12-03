<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "username" => $this->username,
            "role" => User::ROLES[$this->role_id],
            "isActive" => null !== $this->email_verified_at,
            "canBeUpdated" => $this->canBeUpdated ?? true,
            "canBeDeleted" => $this->canBeDeleted ?? true,
        ];
    }
}
