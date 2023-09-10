<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeExperienceRequest extends FormRequest
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
            'company_name' => 'required|string|max:150',
            'company_field' => 'required|string|max:150',
            'responsibility' => 'required|string|max:255',
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
            'start_position' => 'required|string|max:150',
            'end_position' => 'required|string|max:150',
            'stop_reason' => 'required|string|max:255',
            'latest_salary' => 'required|max:18',
        ];
    }
}
