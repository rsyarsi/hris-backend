<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class HumanResourcesTestRequest extends FormRequest
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
            'applied_position' => 'required|string|max:150',
            'date' => 'required|date',
            'source_of_info' => 'nullable',
            'motivation' => 'nullable',
            'self_assessment' => 'nullable',
            'desired_position' => 'nullable',
            'coping_with_department_change' => 'nullable',
            'previous_job_challenges' => 'nullable',
            'reason_for_resignation' => 'nullable',
            'conflict_management' => 'nullable',
            'stress_management' => 'nullable',
            'overtime_availability' => 'nullable',
            'handling_complaints' => 'nullable',
            'salary_expectation' => 'nullable',
            'benefits_facilities' => 'nullable',
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
