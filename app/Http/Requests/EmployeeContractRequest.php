<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EmployeeContractRequest extends FormRequest
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
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date',
            'sk_number' => 'nullable|string|max:50',
            'shift_group_id' => 'nullable|exists:shift_groups,id',
            'umk' => 'nullable|max:18',
            'contract_type_id' => 'nullable|exists:mcontracttypes,id',
            'day' => 'nullable|digits_between:1,11',
            'hour' => 'nullable|digits_between:1,11',
            'hour_per_day' => 'nullable|digits_between:1,11',
            'istirahat_overtime' => 'nullable|max:18',
            'vot1' => 'nullable|max:18',
            'vot2' => 'nullable|max:18',
            'vot3' => 'nullable|max:18',
            'vot4' => 'nullable|max:18',
            'unit_id' => 'nullable|exists:munits,id',
            'department_id' => 'nullable|exists:mdepartments,id',
            'position_id' => 'nullable|exists:mpositions,id',
            'manager_id' => 'nullable|exists:employees,id',
            'supervisor_id' => 'nullable|exists:employees,id',
            'kabag_id' => 'nullable|exists:employees,id',
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
