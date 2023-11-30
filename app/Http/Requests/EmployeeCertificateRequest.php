<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EmployeeCertificateRequest extends FormRequest
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
            'name' => 'required|max:255',
            'institution_name' => 'required|max:255',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date',
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'verified_at' => 'nullable|date',
            'verified_user_Id' => 'nullable|integer',
            'is_extended' => 'nullable|integer',
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
