<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftScheduleRequest extends FormRequest
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
            'shift_id' => 'required|exists:shifts,id',
            'date' => 'required|date',
            'time_in' => 'required|max:45',
            'time_out' => 'required|max:45',
            'shift_exchange_id' => 'required|exists:shifts,id',
            'period' => 'required|max:32',
            'leave_note' => 'required|max:32',
            'holiday' => 'required|integer',
            'night' => 'required|integer',
            'national_holiday' => 'required|integer',
        ];
    }
}
