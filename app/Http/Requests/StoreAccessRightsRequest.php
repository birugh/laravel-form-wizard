<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccessRightsRequest extends FormRequest
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
            // System Access
            'communication_tools' => 'nullable|array',
            'communication_tools.*' => 'string',

            'technical_tools' => 'nullable|array',
            'technical_tools.*' => 'string',

            // Facility Access
            'access_level' => 'nullable|string',

            'specific_zones' => 'nullable|array',
            'specific_zones.*' => 'string',
        ];
    }
}
