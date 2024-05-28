<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class WorkExperienceCandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'candidate_id' => 'required|exists:candidates,id',
            'company' => 'required|string|max:150',
            'position' => 'required|string|max:150',
            'location' => 'required|string|max:255',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'job_description' => 'nullable|string',
            'reason_for_resignation' => 'nullable|string',
            'take_home_pay' => [
                'required',
                'numeric',
                'regex:/^\d{1,16}(\.\d{1,2})?$/'
            ],
        ];
    }

    protected function failedValidation($validator)
    {
        $response = [
            'message' => 'Validation error',
            'error' => true,
            'code' => 422,
            'data' => $validator->errors(),
        ];

        throw new ValidationException(
            $validator,
            response()->json($response, 422)
        );
    }
}
