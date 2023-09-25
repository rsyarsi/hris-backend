<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PayrollComponentRequest extends FormRequest
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
                'max:150',
                'string',
                Rule::unique('mpayrollcomponents')->ignore($this->route('payroll_component')),
            ],
            'active' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => Str::upper($this->input('name')),
        ]);
    }
}