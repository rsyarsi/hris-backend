<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EmergencyContactCandidateRequest extends FormRequest
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
            'relationship_id' => [
                'required',
                'exists:mrelationships,id',
                Rule::unique('emergency_contact_candidates')->where(function ($query) {
                    return $query->where('candidate_id', $this->candidate_id);
                })->ignore($this->route('emergency_contact')),
            ],
            'sex_id' => 'required|exists:msexs,id',
            'name' => 'required|string|max:150',
            'address' => 'nullable|max:255',
            'phone_number' => 'nullable|max:20',
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
