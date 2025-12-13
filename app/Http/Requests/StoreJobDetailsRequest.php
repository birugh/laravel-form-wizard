<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobDetailsRequest extends FormRequest
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
            'department' => 'nullable|string',
            'job_title' => 'nullable|string',
            'join_date' => 'nullable|date',
            'work_arrangement' => 'nullable|in:WFO,Remote,Hybrid',
            'device_request' => 'nullable|in:MacBook,Laptop',
        ];
    }
}
