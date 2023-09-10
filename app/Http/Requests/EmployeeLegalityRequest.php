<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeLegalityRequest extends FormRequest
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
            'legality_type_id' => 'required|exists:mlegalitytypes,id',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date',
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            // 'file_url' => 'nullable|string|max:255',
            // 'file_path' => 'nullable|string|max:255',
            // 'file_disk' => 'nullable|string|max:255',
        ];
    }
}
