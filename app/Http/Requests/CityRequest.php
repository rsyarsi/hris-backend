<?php

namespace App\Http\Requests;

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
        $rules = [
            'code' => 'required|max:4',
            'province_code' => 'required|exists:indonesia_provinces,code',
            'name' => 'required|string|max:255',
            'meta' => 'required',
        ];

        if ($this->isMethod('patch')) {
            $rules['code'] = 'required|max:4|unique:indonesia_cities,code,' . $this->route('cities');
            $rules['name'] = 'required|string|max:255|unique:indonesia_cities,name,' . $this->route('cities');
        }

        return $rules;
    }
}
