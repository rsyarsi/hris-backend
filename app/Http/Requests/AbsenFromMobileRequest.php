<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
        $rules = [
            'Id_schedule' => 'required|exists:shift_schedules,id',
            'Jam' => 'required|string',
            'Function' => 'nullable|string',
            'Type' => 'required|string',
            'Tanggal' => 'nullable|date',
            'Ip_address' => 'nullable|string',
            'Employment_id' => 'nullable|exists:employees,employment_number',
        ];

        // Add a condition based on the value of Type
        if ($this->input('Type') === 'SPL') {
            $rules['Overtime_id'] = 'required|exists:overtimes,id';
        } else {
            $rules['Overtime_id'] = 'nullable|exists:overtimes,id';
        }

        return $rules;
    }

    protected function failedValidation($validator)
    {
        $response = [
            'message' => 'Validation Error, please check your data!',
            'success' => false,
            'code' => 422,
            'data' => [],
        ];

        throw new ValidationException(
            $validator,
            response()->json($response, 422)
        );
    }
}
