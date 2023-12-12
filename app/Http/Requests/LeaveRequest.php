<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Rules\{DateSmallerThan, NotOverlappingPermissionsLeaves};

class LeaveRequest extends FormRequest
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
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'leave_status_id' => 'required|exists:leave_statuses,id',
            'from_date' => [
                'required',
                'date',
                new DateSmallerThan('to_date'),
            ],
            // 'from_date' => 'required|date',
            'to_date' => 'required|date',
            'note' => 'required',
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:5048',
        ];

        // Add a condition based on the value of Type
        $leaveTypeId = (int) $this->input('leave_type_id');

        // Add a condition based on the value of Type
        if ($leaveTypeId === 2) {
            $rules['file'] = 'required|mimes:jpeg,png,jpg,gif,pdf|max:5048';
        } else {
            $rules['file'] = 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:5048';
        }

        // Conditionally apply the NotOverlappingPermissionsLeaves rule for creating new records
        // if ($this->isMethod('post')) {
        //     $rules['from_date'][] = new NotOverlappingPermissionsLeaves(
        //         $this->input('employee_id'),
        //         $this->input('from_date'),
        //         $this->input('to_date')
        //     );
        // }

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
