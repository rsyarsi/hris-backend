<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Rules\UniqueShiftScheduleExchangeDateRange;

class ShiftScheduleExchangeRequest extends FormRequest
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
        $rules = [
            'shift_exchange_type' => 'nullable|in:TUKAR SHIFT,LIBUR',
            'employe_requested_id' => 'nullable|exists:employees,id',
            'shift_schedule_date_requested' => 'nullable|date',
            'to_employee_id' => 'nullable|exists:employees,id',
            'to_shift_schedule_date' => 'nullable|date',
            'exchange_employee_id' => 'nullable|exists:employees,id',
            'exchange_date' => 'nullable|date',
            'date_created' => 'nullable|date',
            'date_updated' => 'nullable|date',
            'cancel' => 'nullable|integer',
            'notes' => 'nullable|string|max:255',
        ];

        if ($this->isMethod('post')) {
            $rules['shift_schedule_date_requested'] = new UniqueShiftScheduleExchangeDateRange();
        }

        return $rules;
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
