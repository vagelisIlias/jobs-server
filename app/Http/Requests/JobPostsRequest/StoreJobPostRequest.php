<?php

namespace App\Http\Requests\JobPostsRequest;

use App\Models\JobPost;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreJobPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string','max:50', 'unique:job_posts,title'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'location' => ['required', 'string',],
            'department' => ['required', 'string', 'max:255'],
        ];
    }

    public function storeJobPost(): JobPost
    {
        /** @var \App\Models\User $user */
        $userId = Auth::user()->id;

        return JobPost::create([
            'user_id' => $userId,
            'title' => $this->title,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'location' => $this->location,
            'department' => $this->department,
            'slug' => Str::slug($this->title),
        ]);
    }
}
