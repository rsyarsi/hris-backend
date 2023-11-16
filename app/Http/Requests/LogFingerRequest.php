<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogFingerRequest extends FormRequest
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
            'log_at' => 'required|date',
            'employee_id' => 'required|exists:employees,id',
            'in_out' => 'required|integer',
            'code_sn_finger' => 'required|string|max:45',
            'datetime' => 'required|date',
            'manual' => 'required|integer',
            'code_pin' => 'required|string|max:45',
        ];
    }
}
