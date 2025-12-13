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
            'communication_tools' => 'sometimes|required|array',
            'communication_tools.*' => 'string',

            'technical_tools' => 'sometimes|required|array',
            'technical_tools.*' => 'string',

            // Facility Access
            'access_level' => 'sometimes|required|string',

            'specific_zones' => 'sometimes|required|array',
            'specific_zones.*' => 'string',
        ];
    }
}
