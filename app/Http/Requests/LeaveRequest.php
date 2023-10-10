<?php

namespace App\Http\Requests;

use App\Rules\{DateSmallerThan, NotOverlappingPermissions};
use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'leave_status_id' => 'required|exists:leave_statuses,id',
            'from_date' => ['required',
                            'date',
                            new NotOverlappingPermissions(
                                $this->input('employee_id'),
                                $this->input('from_date'),
                                $this->input('to_date')
                            ),
                            new DateSmallerThan('to_date'),
                        ],
            'to_date' => 'required|date',
            'note' => 'required',
        ];
    }
}
