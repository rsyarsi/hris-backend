<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ImportPengembalianRequest extends FormRequest
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
            'file' => [
                'required',
                'file', // Ensure it's a file upload
                'mimes:xlsx,csv,txt', // Allow only Excel files (xlsx)
                'max:2048', // Limit the file size (2MB in this example, adjust as needed)
            ],
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'The file field is required.',
            'file.mimes' => 'Only Excel files (xlsx) and csv are allowed.',
            'file.max' => 'The file may not be greater than 2MB.',
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
