<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeSkillRequest extends FormRequest
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
            'skill_type_id' => 'required|exists:mskilltypes,id',
            'employee_certificate_id' => 'nullable|exists:employee_certificates,id',
            'description' => 'nullable|max:255',
            'level' => 'nullable|max:255',
        ];
    }
}
