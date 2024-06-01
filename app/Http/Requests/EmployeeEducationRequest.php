<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EmployeeEducationRequest extends FormRequest
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
            'education_id' => 'required|exists:meducations,id',
            'institution_name' => 'required|string|max:255',
            'major' => 'nullable|string|max:255',
            'started_year' => 'nullable|digits_between:1,10',
            'ended_year' => 'nullable|digits_between:1,10',
            'is_passed' => 'nullable|integer',
            'verified_at' => 'nullable|date',
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048',
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
