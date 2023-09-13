<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'code' => [
                'required',
                'max:4',
                'string',
                Rule::unique('indonesia_cities')->ignore($this->route('city')),
            ],
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('indonesia_cities')->ignore($this->route('city')),
            ],
            'province_code' => 'required|exists:indonesia_provinces,code',
            'meta' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'code' => Str::upper($this->input('code')),
            'name' => Str::upper($this->input('name')),
        ]);
    }
}
