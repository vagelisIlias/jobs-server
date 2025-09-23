<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\JobPostsRequest;

use App\Models\JobPost;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobPostRequest extends FormRequest
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
            'title' => ['string','max:50', Rule::unique('job_posts', 'title')->where('user_id', $this->input('user_id'))],
            'description' => ['string'],
            'requirements' => ['string'],
            'location' => ['string'],
            'department' => ['string', 'max:50'],
        ];
    }

    public function updateJobPost(JobPost $job): JobPost
    {
        $job->update($this->only([
            'title',
            'description',
            'requirements',
            'location',
            'department',
        ]));

        return $job;
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
