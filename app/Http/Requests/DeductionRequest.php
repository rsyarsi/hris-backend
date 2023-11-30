<?php

namespace App\Http\Requests;

use App\Rules\UniqueDeductionRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class DeductionRequest extends FormRequest
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
        $deductionId = $this->route('deduction');
        return [
            'employee_id' => 'required|exists:employees,id',
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
                new UniqueDeductionRule(
                    $this->input('employee_id'),
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
