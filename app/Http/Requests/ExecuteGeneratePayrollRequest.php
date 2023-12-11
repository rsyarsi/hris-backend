<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ExecuteGeneratePayrollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'periode_absen' => 'required|string|max:20',
            'periode_payroll' => 'required|string|max:20',
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
