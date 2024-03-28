<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EducationBackgroundCandidateRequest extends FormRequest
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
            'education_id' => [
                'required',
                'exists:meducations,id',
                Rule::unique('education_background_candidates')->where(function ($query) {
                    return $query->where('candidate_id', $this->candidate_id);
                })->ignore($this->route('education_background_candidate')),
            ],
            'institution_name' => 'required|string|max:150',
            'major' => 'nullable|max:150',
            'started_year' => 'required|date_format:Y',
            'ended_year' => 'required|date_format:Y',
            'final_score' => 'nullable|date_format:Y',
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
