<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DistrictRequest extends FormRequest
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
                'max:7',
                'string',
                Rule::unique('indonesia_districts')->ignore($this->route('district')),
            ],
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('indonesia_districts')->ignore($this->route('district')),
            ],
            'city_code' => 'required|exists:indonesia_cities,code',
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
