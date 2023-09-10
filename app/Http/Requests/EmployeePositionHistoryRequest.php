<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeePositionHistoryRequest extends FormRequest
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
            'position_id' => 'required|exists:mpositions,id',
            'unit_id' => 'required|exists:munits,id',
            'department_id' => 'required|exists:mdepartments,id',
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
        ];
    }
}
