<?php

namespace App\Http\Requests;

use App\Rules\NotOverlappingShiftSchedules;
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
            'date' => [
                'nullable',
                'date',
                new NotOverlappingShiftSchedules,
            ],
            'start_date' => [
                'nullable',
                'date',
                new NotOverlappingShiftSchedules,
            ],
            'end_date' => 'nullable|date',
            'time_in' => 'nullable|max:45',
            'time_out' => 'nullable|max:45',
            'shift_exchange_id' => 'nullable|exists:shifts,id',
            'period' => 'nullable|max:32',
            'late_note' => 'nullable|max:150',
            'leave_note' => 'nullable|max:32',
            'holiday' => 'nullable|integer',
            'night' => 'nullable|integer',
            'national_holiday' => 'nullable|integer',
        ];
    }
}
