<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CandidateRequest extends FormRequest
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
            'candidate_account_id' => [
                'nullable',
                'exists:candidate_accounts,id',
                Rule::unique('candidates')->ignore($this->route('candidate')),
            ],
            'first_name' => 'required|string|max:150',
            'middle_name' => 'nullable|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'sex_id' => 'required|exists:msexs,id',
            'legal_identity_type_id' => 'required|exists:midentitytypes,id',
            'legal_identity_number' => 'required|string|max:150',
            'legal_address' => 'nullable|string',
            'current_address' => 'nullable|string',
            'home_phone_number' => 'nullable|max:20',
            'phone_number' => 'nullable|max:20',
            'email' => [
                'nullable',
                'max:50',
                'string',
                Rule::unique('candidates')->ignore($this->route('candidate')),
            ],
            'birth_place' => 'required|string|max:50',
            'birth_date' => 'required|date',
            'age' => 'nullable|numeric|digits_between:1,10',
            'marital_status_id' => 'required|exists:mmaritalstatuses,id',
            'ethnic_id' => 'required|exists:methnics,id',
            'religion_id' => 'required|exists:mreligions,id',
            'tax_identify_number' => 'nullable|string|max:150',
            'weight' => 'nullable|numeric|digits_between:1,10',
            'height' => 'nullable|numeric|digits_between:1,10',
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
