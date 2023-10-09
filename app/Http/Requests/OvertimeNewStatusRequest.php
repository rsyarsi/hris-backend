<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OvertimeNewStatusRequest extends FormRequest
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
            'overtime_status_id' => 'required|exists:overtime_statuses,id',
        ];
    }
}
