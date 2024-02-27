<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateGenerateAbsenRequest extends FormRequest
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
            'employee_id' => 'nullable|exists:employees,id',
            'shift_schedule_id' => 'nullable|exists:shift_schedules,id',
            'period' => 'nullable|date_format:Y-m',
            'date' => 'nullable|date',
            'date_in_at' => 'nullable|date',
            'time_in_at' => 'nullable|max:50',
            'date_out_at' => 'nullable|date',
            'time_out_at' => 'nullable|max:50',
            'telat' => 'nullable|numeric',
            'pa' => 'nullable|numeric',
            'holiday' => 'nullable|in:1,0',
            'national_holiday' => 'nullable|in:1,0',
            'note' => 'nullable',
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
