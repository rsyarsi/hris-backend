<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeFamilyRequest extends FormRequest
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
            'name' => 'required|string|max:150',
            'relationship_id' => 'required|exists:mrelationships,id',
            'as_emergency' => 'required|integer',
            'is_dead' => 'required|integer',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|max:20',
            'phone_country' => 'nullable|max:5',
            'address' => 'nullable|max:255',
            'postal_code' => 'nullable|max:10',
            'province_id' => 'nullable|exists:indonesia_provinces,id',
            'city_id' => 'nullable|exists:indonesia_cities,id',
            'district_id' => 'nullable|exists:indonesia_districts,id',
            'village_id' => 'nullable|exists:indonesia_villages,id',
            'job_id' => 'nullable|exists:mjobs,id',
        ];
    }
}
