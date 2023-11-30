<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\UniquePayrollComponent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EmployeeContractDetailRequest extends FormRequest
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
            'employee_contract_id' => 'required|exists:employee_contracts,id',
            'payroll_component_id' => [
                'required',
                'exists:mpayrollcomponents,id',
                Rule::unique('employee_contract_details')
                ->where(function ($query) {
                    $query->where('employee_contract_id', request()->input('employee_contract_id'))
                        ->where('payroll_component_id', request()->input('payroll_component_id'));
                }),
            ],
            'nominal' => 'required|max:18',
            'active' => 'required|integer',
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
