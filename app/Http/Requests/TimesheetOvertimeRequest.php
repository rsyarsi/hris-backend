<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class TimesheetOvertimeRequest extends FormRequest
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
            'employee_name' => 'nullable|string|max:255',
            'unitname' => 'nullable|string|max:255',
            'positionname' => 'nullable|string|max:255',
            'departmenname' => 'nullable|string|max:255',
            'overtime_type' => 'nullable|string|max:255',
            'realisasihours' => 'nullable|string|max:255',
            'schedule_date_in_at' => 'nullable|date',
            'schedule_time_in_at' => 'nullable|string|max:255',
            'schedule_date_out_at' => 'nullable|date',
            'schedule_time_out_at' => 'nullable|string|max:255',
            'date_in_at' => 'nullable|date',
            'time_in_at' => 'nullable|string|max:255',
            'date_out_at' => 'nullable|date',
            'time_out_at' => 'nullable|string|max:255',
            'jamlemburawal' => 'nullable|string|max:255',
            'jamlemburconvert' => 'nullable|numeric',
            'jamlembur' => 'nullable|string|max:255',
            'tuunjangan' => 'nullable|numeric',
            'uanglembur' => 'nullable|numeric',
            'period' => 'nullable|string|max:255',
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
