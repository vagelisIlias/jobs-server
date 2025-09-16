<?php

namespace App\Http\Resources\Api\V1\JobResource;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobPostResource extends JsonResource
{
    // public static $wrap = 'jobposts';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'jobposts',
            'id' => $this->id,
            'attributes' => [
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
            ],
            'relationships' => [
                'data' => [
                    'type' => 'user',
                     'user_id' => $this->user_id,
                ]
            ],
            "links" => [
                ['self' => route('jobs.show', ['job' => $this->slug])]
            ],
        ];
    }
}
