<?php

namespace App\Http\Requests;

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
        $rules = [
            'code' => 'required|max:7',
            'city_code' => 'required|exists:indonesia_cities,code',
            'name' => 'required|string|max:255',
            'meta' => 'required',
        ];

        if ($this->isMethod('patch')) {
            $rules['code'] = 'required|max:7|unique:indonesia_districts,code,' . $this->route('districts');
            $rules['name'] = 'required|string|max:255|unique:indonesia_districts,name,' . $this->route('districts');
        }

        return $rules;
    }
}
