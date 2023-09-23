<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OvertimeRequest extends FormRequest
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
            'task' => 'required|max:255',
            'note' => 'required|max:255',
            'overtime_status_id' => 'required|exists:overtime_statuses,id',
            'amount' => 'required|max:18',
            'type' => 'required|in:HARI-KERJA,HARI-LIBUR',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ];
    }
}
