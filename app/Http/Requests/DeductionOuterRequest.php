<?php

namespace App\Http\Requests;

use App\Rules\UniqueDeductionOuterRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class DeductionOuterRequest extends FormRequest
{
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
        $deductionId = $this->route('deduction');
        return [
            'employee_number' => 'required|exists:employees,employment_number',
            'nilai' => 'required|integer',
            'keterangan' => 'nullable|max:255',
            'tenor' => 'required|integer',
            'pembayaran' => 'nullable|integer',
            'sisa' => 'nullable|integer',
            'kode_lunas' => 'nullable|max:255',
            'period' => [
                'required',
                'string',
                'max:45',
                new UniqueDeductionOuterRule(
                    $this->input('employee_number'),
                    $this->input('period'),
                    $deductionId // Pass the deduction ID for exclusion on update
                )
            ],
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
