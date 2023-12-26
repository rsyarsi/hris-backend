<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'shift_exchange_type' => 'nullable|max:50',
            'employe_requested_id' => 'nullable|exists:employees,id',
            'shift_schedule_date_requested' => 'nullable|date',
            'to_employee_id' => 'nullable|exists:employees,id',
            'to_shift_schedule_date' => 'nullable|date',
            'exchange_employee_id' => 'nullable|exists:employees,id',
            'exchange_date' => 'nullable|date',
            'date_created' => 'nullable|date',
            'date_updated' => 'nullable|date',
            'cancel' => 'nullable|integer',
        ];
    }
}
