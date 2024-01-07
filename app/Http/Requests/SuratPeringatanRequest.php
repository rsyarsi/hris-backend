<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SuratPeringatanRequest extends FormRequest
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
            'date' => 'nullable|date',
            'no_surat' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'jenis_pelanggaran' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'batal' => 'nullable|string|max:255',
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048',
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
