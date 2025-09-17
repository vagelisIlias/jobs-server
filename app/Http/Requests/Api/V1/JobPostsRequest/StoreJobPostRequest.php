<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\JobPostsRequest;

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
            'title' => ['required', 'string','max:50'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'location' => ['required', 'string',],
            'department' => ['required', 'string', 'max:50'],
        ];
    }

    public function storeJobPost(): JobPost
    {
        return JobPost::create([
            'user_id' => Auth::user()->id,
            'title' => $this->title,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'location' => $this->location,
            'department' => $this->department,
            'slug' => Str::slug($this->title),
        ]);
    }

    /**
     * Customize the validation messages.
     */
    public function messages(): array
    {
        return [
            'title.max' => 'The title may not be greater than 50 characters.',
            'department.max' => 'The department may not be greater than 50 characters.',
        ];
    }
}
