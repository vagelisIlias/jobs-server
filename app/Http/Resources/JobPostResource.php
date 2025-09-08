<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class JobPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\User $user */
        $userId = Auth::user()->id;

        return [
            'id' => $this->id,
            'user_id' => $userId,
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'requirements' => $this->requirements,
            'location' => $this->location,
            'department' => $this->department,
            'employment_type' => $this->employment_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
