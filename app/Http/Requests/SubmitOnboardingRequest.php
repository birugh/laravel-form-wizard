<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitOnboardingRequest extends FormRequest
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
    public static function rules(): array
    {
        return [
            // step 1
            'personal_information.name' => 'required|string',
            'personal_information.email' => 'required|email',
            'personal_information.phone' => 'required|string',
            'personal_information.emergency_contact' => 'required|string',

            // step 2
            'job_details.department' => 'required|string',
            'job_details.job_title' => 'required|string',
            'job_details.join_date' => 'required|date',
            'job_details.work_arrangement' => 'required|in:WFO,Remote,Hybrid',
            'job_details.device_request' => 'required|in:MacBook,Laptop',

            // step 3
            'access_rights.communication_tools' => 'required|array',
            'access_rights.communication_tools.*' => 'string',

            'access_rights.technical_tools' => 'required|array',
            'access_rights.technical_tools.*' => 'string',

            'access_rights.access_level' => 'required|string',

            'access_rights.specific_zones' => 'required|array',
            'access_rights.specific_zones.*' => 'string',
        ];
    }
}
