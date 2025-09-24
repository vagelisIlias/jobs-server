<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\UserResource;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    // public static $wrap = 'user';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'first_name' => $this->when($this->isOwner(), $this->first_name),
            'last_name' => $this->when($this->isOwner(), $this->last_name),
            'user_name' => $this->user_name,
            'slug' => $this->when($this->isOwner(), Str::slug($this->slug)),
            'email' => $this->when($this->isOwner(), $this->email),
            'email_verified_at' => $this->when($this->isOwner(), $this->email_verified_at),
            'created_at' => $this->when($this->isOwner(), $this->created_at),
            'updated_at' => $this->when($this->isOwner(), $this->updated_at),
            'role' => $this->when($this->isOwner(), $this->role),
            'status' => $this->when($this->isOwner(), $this->status),
        ];
    }

    private function isOwner(): bool
    {
        return Auth::check() && Auth::id() === $this->id;
    }
}
