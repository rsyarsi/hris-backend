<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PromotionDemotionRequest extends FormRequest
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
            'employee_id' => 'required|exists:employees,id',
            'type' => 'nullable|in:DEMOSI,PROMOSI',
            'unit_id' => 'required|exists:munits,id',
            'before_position_id' => 'required|exists:mpositions,id',
            'after_position_id' => 'required|exists:mpositions,id',
            'date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
            'no_sk' => 'nullable|string|max:255',
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
