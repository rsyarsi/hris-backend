<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeOrganizationRequest extends FormRequest
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
            'institution_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'started_year' => 'required|integer|digits_between:1,10',
            'ended_year' => 'required|integer|digits_between:1,10',
        ];
    }
}
