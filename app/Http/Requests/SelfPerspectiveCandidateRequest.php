<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SelfPerspectiveCandidateRequest extends FormRequest
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
            'self_perspective' => 'nullable|string',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'successes' => 'nullable|string',
            'failures' => 'nullable|string',
            'career_overview' => 'nullable|string',
            'future_expectations' => 'nullable|string',
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
