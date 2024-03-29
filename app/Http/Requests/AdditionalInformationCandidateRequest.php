<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AdditionalInformationCandidateRequest extends FormRequest
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
            'physical_condition' => 'nullable|string',
            'severe_diseases' => 'nullable|string',
            'hospitalizations' => 'nullable|string',
            'last_medical_checkup' => 'nullable|string',
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
