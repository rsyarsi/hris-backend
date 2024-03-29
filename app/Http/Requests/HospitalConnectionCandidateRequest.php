<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class HospitalConnectionCandidateRequest extends FormRequest
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
            'relationship_id' => 'required|exists:mrelationships,id',
            'name' => 'required|string|max:150',
            'department_id' => 'required|exists:mdepartments,id',
            'position_id' => 'nullable|exists:mpositions,id'
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
