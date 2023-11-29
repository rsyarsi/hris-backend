<?php

namespace App\Http\Requests;

use App\Rules\UniquePphRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PphRequest extends FormRequest
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
        $pphId = $this->route('pph');
        return [
            'employee_id' => 'required|exists:employees,id',
            'nilai' => 'required|integer',
            'period' => [
                'required',
                'string',
                'max:45',
                new UniquePphRule(
                    $this->input('employee_id'),
                    $this->input('period'),
                    $pphId // Pass the pph ID for exclusion on update
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
