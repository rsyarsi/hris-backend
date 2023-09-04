<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:150',
            'legal_indentity_type_id',
            'legal_identity_number' => 'required|string|max:150',
            'family_card_number' => 'required|string|max:150',
            'sex_id' => 'required|exists:msexs,id',
            'birth_place' => 'required|string|max:50',
            'birth_date' => 'required|date',
            'marital_status_id' => 'required|exists:mmaritalstatuses,id',
            'religion_id' => 'required|exists:mreligions,id',
            'blood_type' => 'required|string|max:15',
            'tax_identify_number' => 'required|string|max:150',
            'email' => 'required|email|unique:employees,email|max:150',
            'phone_number' => 'required|max:20',
            'phone_number_country' => 'required|max:5',
            'legal_address' => 'required|string|max:255',
            'legal_postal_code' => 'required|max:10',
            'legal_province_id' => 'required|exists:indonesia_provinces,id',
            'legal_city_id' => 'required|exists:indonesia_cities,id',
            'legal_district_id' => 'required|exists:indonesia_districts,id',
            'legal_village_id' => 'required|exists:indonesia_villages,id',
            'legal_home_phone_number' => 'required|max:15',
            'legal_home_phone_country' => 'required|max:15',
            'current_address' => 'required|string|max:255',
            'current_postal_code' => 'required|max:10',
            'current_province_id' => 'required|exists:indonesia_provinces,id',
            'current_city_id' => 'required|exists:indonesia_cities,id',
            'current_district_id' => 'required|exists:indonesia_districts,id',
            'current_village_id' => 'required|exists:indonesia_villages,id',
            'current_home_phone_number' => 'required|max:15',
            'current_home_phone_country' => 'required|max:15',
            'status_employment_id' => 'required|exists:mstatusemployments,id',
            'position_id' => 'required|exists:mpositions,id',
            'unit_id' => 'required|exists:munits,id',
            'department_id' => 'required|exists:mdepartments,id',
            'started_at' => 'required|date',
            'employment_number' => 'required|max:36',
            'resigned_at' => 'nullable|date',
            'user_id' => 'required|exists:users,id'
        ];

        if ($this->isMethod('patch')) {
            $rules['email'] = 'required|string|max:150|unique:employees,email,' . $this->route('employees');
        }

        return $rules;
    }
}
