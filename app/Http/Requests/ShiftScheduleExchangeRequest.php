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
            'employe_requested_id' => 'nullable|exists:employees,id',
            'shift_schedule_date_requested' => 'nullable|date',
            'shift_schedule_request_id' => 'nullable|exists:shift_schedules,id',
            'shift_schedule_code_requested' => 'nullable|max:50',
            'shift_schedule_name_requested' => 'nullable|max:255',
            'shift_schedule_time_from_requested' => 'nullable|max:50',
            'shift_schedule_time_end_requested' => 'nullable|max:50',
            'shift_exchange_type' => 'nullable|max:50',
            'to_employee_id' => 'nullable|exists:employees,id',
            'shift_schedule_date_to' => 'nullable|date',
            'to_shift_schedule_id' => 'nullable|exists:shift_schedules,id',
            'to_shift_schedule_code' => 'nullable|max:50',
            'to_shift_schedule_name' => 'nullable|max:255',
            'to_shift_schedule_time_from' => 'nullable|max:50',
            'to_shift_schedule_time_end' => 'nullable|max:50',
            'exchange_employee_id' => 'nullable|exists:employees,id',
            'exchange_date' => 'nullable|date',
            'exchange_shift_schedule_id' => 'nullable|exists:shift_schedules,id',
            'exchange_shift_schedule_code' => 'nullable|max:50',
            'exchange_shift_schedule_name' => 'nullable|max:255',
            'exchange_shift_schedule_time_from' => 'nullable|max:50',
            'exchange_shift_schedule_time_end' => 'nullable|max:50',
            'date_created' => 'nullable|max:50',
            'date_updated' => 'nullable|max:50',
            'user_created_id' => 'nullable|exists:users,id',
            'user_updated_id' => 'nullable|exists:users,id',
            'cancel' => 'nullable|max:50',
        ];
    }
}
