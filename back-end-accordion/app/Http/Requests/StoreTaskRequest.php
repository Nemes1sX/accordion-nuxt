<?php

namespace App\Http\Requests;

use App\Rules\TaskLimitRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
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
            'name' => ['required', Rule::min(8)->max(255)],
            'description' => ['required', Rule::min(8)],
            'user_id' => ['required', new TaskLimitRule]
        ];
    }
}
