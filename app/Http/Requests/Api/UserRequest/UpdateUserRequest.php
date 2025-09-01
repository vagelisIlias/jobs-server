<?php

namespace App\Http\Requests\Api\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'user_name' => $this->user_name,
            'slug' => $this->user_name,
            'updated_at' => $this->updated_at,
        ];
    }
}
