<?php

namespace App\Http\Requests;

use App\Rules\UniquePengembalianPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PengembalianRequest extends FormRequest
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
            'payroll_period' => 'required',
            'amount' => 'required|numeric',
        ];

        if ($this->isMethod('post')) {
            $rules['payroll_period'] = new UniquePengembalianPeriod();
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
