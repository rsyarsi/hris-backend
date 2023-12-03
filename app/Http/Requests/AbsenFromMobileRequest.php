<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbsenFromMobileRequest extends FormRequest
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
            'Id_schedule' => 'required|exists:shift_schedules,id',
            'Jam' => 'required|string',
            'Function' => 'required|string',
            'Type' => 'required|string',
            'Tanggal' => 'required|date',
            'Ip_address' => 'required|string',
            'Employment_id' => 'required|exists:employees,employment_number',
            'Overtime_id' => 'required|exists:overtimes,id',
        ];
    }
}
