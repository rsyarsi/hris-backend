<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeEducationRequest extends FormRequest
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
            'education_id' => 'required|exists:meducations,id',
            'institution_name' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'started_year' => 'required|digits_between:1,10',
            'ended_year' => 'required|digits_between:1,10',
            'is_passed' => 'required|integer',
            'verified_at' => 'required|date',
        ];
    }
}
