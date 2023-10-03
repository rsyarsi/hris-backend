<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('permissions')->ignore($this->route('permissions')),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => Str::lower($this->input('name')),
        ]);
    }
}
