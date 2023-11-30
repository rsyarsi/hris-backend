<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
        return [
            'name' => 'required|string|max:150',
            'legal_identity_type_id' => 'required|exists:midentitytypes,id',
            'legal_identity_number' => 'required|string|max:150',
            'family_card_number' => 'nullable|string|max:150',
            'sex_id' => 'required|exists:msexs,id',
            'birth_place' => 'required|string|max:50',
            'birth_date' => 'required|date',
            'marital_status_id' => 'required|exists:mmaritalstatuses,id',
            'religion_id' => 'required|exists:mreligions,id',
            'blood_type' => 'nullable|string|max:15',
            'tax_identify_number' => 'nullable|string|max:150',
            'email' => [
                'nullable',
                'max:150',
                'string',
                Rule::unique('employees')->ignore($this->route('employee')),
            ],
            'phone_number' => 'nullable|max:20',
            'phone_number_country' => 'nullable|max:5',
            'legal_address' => 'nullable|string|max:255',
            'legal_postal_code' => 'nullable|max:10',
            'legal_province_id' => 'nullable|exists:indonesia_provinces,id',
            'legal_city_id' => 'nullable|exists:indonesia_cities,id',
            'legal_district_id' => 'nullable|exists:indonesia_districts,id',
            'legal_village_id' => 'nullable|exists:indonesia_villages,id',
            'legal_home_phone_number' => 'nullable|max:15',
            'legal_home_phone_country' => 'nullable|max:15',
            'current_address' => 'nullable|string|max:255',
            'current_postal_code' => 'nullable|max:10',
            'current_province_id' => 'nullable|exists:indonesia_provinces,id',
            'current_city_id' => 'nullable|exists:indonesia_cities,id',
            'current_district_id' => 'nullable|exists:indonesia_districts,id',
            'current_village_id' => 'nullable|exists:indonesia_villages,id',
            'current_home_phone_number' => 'nullable|max:15',
            'current_home_phone_country' => 'nullable|max:15',
            'status_employment_id' => 'nullable|exists:mstatusemployments,id',
            'position_id' => 'nullable|exists:mpositions,id',
            'unit_id' => 'nullable|exists:munits,id',
            'department_id' => 'nullable|exists:mdepartments,id',
            'started_at' => 'nullable|date',
            'employment_number' => [
                'nullable',
                'max:36',
                'string',
                Rule::unique('employees')->ignore($this->route('employee')),
            ],
            'user_id' => [
                'nullable',
                'max:36',
                'string',
                'exists:users,id',
                Rule::unique('employees')->ignore($this->route('employee')),
            ],
            'resigned_at' => 'nullable|date',
            'supervisor_id' => 'nullable|exists:employees,id',
            'manager_id' => 'nullable|exists:employees,id',
            'shift_group_id' => 'nullable|exists:shift_groups,id',
            'pin' => [
                'nullable',
                'integer',
                Rule::unique('employees')->ignore($this->route('employee')),
            ],
        ];
    }

    protected function failedValidation($validator)
    {
        $response = [
            'message' => 'Validation error',
            'error' => true,
            'code' => 422,
            'data' => $validator->errors(),
        ];

        throw new ValidationException(
            $validator,
            response()->json($response, 422)
        );
    }
}
