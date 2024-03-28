<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class FamilyInformationCandidateRequest extends FormRequest
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
                Rule::unique('family_information_candidates')->where(function ($query) {
                    return $query->where('candidate_id', $this->candidate_id);
                })->ignore($this->route('family_information_candidates')),
            ],
            'name' => 'required|string|max:150',
            'sex_id' => 'required|exists:msexs,id',
            'birth_place' => 'nullable|max:50',
            'birth_date' => 'nullable|date',
            'education_id' => 'required|exists:meducations,id',
            'job_id' => 'required|exists:mjobs,id',
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
