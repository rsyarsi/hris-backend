<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'password' => [Rule::requiredIf($this->isMethod('post')), 'string', 'max:255'],
            'username' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'user_device_id' => 'nullable|string|max:255',
            'firebase_id' => 'nullable|string|max:255',
            'imei' => 'nullable|string|max:255',
            'ip' => 'nullable|string|max:255',
            'role' => 'nullable|exists:roles,name',
            'employee_id' => 'nullable|exists:employees,id',
            'administrator' => 'nullable|in:1,0',
            'hrd' => 'nullable|in:1,0',
            'manager' => 'nullable|in:1,0',
            'supervisor' => 'nullable|in:1,0',
            'pegawai' => 'nullable|in:1,0',
            'kabag' => 'nullable|in:1,0',
            'staf' => 'nullable|in:1,0',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => Str::upper($this->input('name')),
        ]);
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
