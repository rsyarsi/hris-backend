<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class GeneratePayrollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            // 'employee_day_works' => 'nullable|numeric',
            'employee_fix_gapok' => 'nullable|numeric',
            'employee_fix_transport' => 'nullable|numeric',
            'employee_fix_uangmakan' => 'nullable|numeric',
            'employee_fix_tunjangankemahalan' => 'nullable|numeric',
            // 'fix_income_total' => 'nullable|numeric',
            'employee_tunjangan_hdm' => 'nullable|numeric',
            'employee_tunjangan_jabatan' => 'nullable|numeric',
            'employee_tunjangan_dinasmalam' => 'nullable|numeric',
            'employee_tunjangan_tunjanganppr' => 'nullable|numeric',
            'employee_tunjangan_intensifkhusus' => 'nullable|numeric',
            'employee_tunjangan_extrafooding' => 'nullable|numeric',
            'employee_tunjangan_lembur' => 'nullable|numeric',
            // 'tunjangan_total' => 'nullable|numeric',
            // 'salary_bruto' => 'nullable|numeric',
            'kelebihanpotongan' => 'nullable|numeric',
            'liability_companies_jkk' => 'nullable|numeric',
            'liability_companies_jkm' => 'nullable|numeric',
            'liability_companies_jht' => 'nullable|numeric',
            'liability_companies_jp' => 'nullable|numeric',
            'liability_companies_bpjskesehatan' => 'nullable|numeric',
            'liability_companies_total' => 'nullable|numeric',

            'liability_employee_potongan' => 'nullable|numeric',
            'liability_employee_jht' => 'nullable|numeric',
            'liability_employee_jp' => 'nullable|numeric',
            'liability_employee_bpjskesehatan' => 'nullable|numeric',
            'liability_employee_pph21' => 'nullable|numeric',
            'liability_employee_foods' => 'nullable|numeric',
            'liability_employee_absens' => 'nullable|numeric',
            // 'liability_employee_total' => 'nullable|numeric',
            // 'salary_total' => 'nullable|numeric',
            // 'salary_total_before_zakat' => 'nullable|numeric',
            'thr' => 'nullable|numeric',
            'zakat' => 'nullable|numeric',
            'notes' => 'nullable|string',
            // 'period_payroll' => 'nullable|string',
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
