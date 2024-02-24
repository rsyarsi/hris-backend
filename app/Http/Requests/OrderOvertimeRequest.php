<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\DateSmallerThan;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class OrderOvertimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        // Retrieve the unit_id from the employee_id
        $employee = Employee::find($this->input('employee_id'));
        $unitId = $employee->unit_id ?? null;

        // Define the default rules
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'task' => 'required|max:255',
            'note' => 'required|max:255',
            'overtime_status_id' => 'required|exists:overtime_statuses,id',
            'amount' => 'required|max:18',
            'type' => 'required|string|max:255',
            'from_date' => [
                'required',
                'date',
                new DateSmallerThan('to_date')
            ],
            'to_date' => 'required|date',
            'libur' => 'required|in:0,1',
        ];

        // if ($this->isMethod('post')) {
        //     $rules['from_date'][] = new UniqueOvertimeDateRange();
        // }

        if ($this->input('type') === 'ONCALL' && $unitId !== 19) {
            throw ValidationException::withMessages(['type' => 'Type ONCALL hanya untuk unit HEMODIALISA.']);
        }

        return $rules;
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
