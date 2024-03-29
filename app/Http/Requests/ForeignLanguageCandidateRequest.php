<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ForeignLanguageCandidateRequest extends FormRequest
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
            'language' => 'required|string|max:150',
            'speaking_ability_level' => 'nullable|in:GOOD,FAIR,POOR',
            'writing_ability_level' => 'nullable|in:GOOD,FAIR,POOR',
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
