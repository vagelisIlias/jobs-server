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
            'attributes' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'user_name' => $this->user_name,
                'slug' => $this->when(Auth::check() && Auth::id() === $this->id, Str::slug($this->slug)),
                'email' => $this->when(Auth::check() && Auth::id() === $this->id, $this->email),
                $this->mergeWhen($request->routeIs('users.*'), [
                    'email_verified_at' => $this->when($request->user()?->id === $this->id, $this->email_verified_at),
                    'created_at' => $this->when(Auth::check() && Auth::id() === $this->id, $this->created_at),
                    'updated_at' => $this->when(Auth::check() && Auth::id() === $this->id, $this->updated_at),
                ]),
                'role' => $this->when(Auth::check() && Auth::id() === $this->id, $this->role),
                'status' => $this->when(Auth::check() && Auth::id() === $this->id, $this->status),
            ],
        ];
    }
}
