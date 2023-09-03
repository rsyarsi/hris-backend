<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvinceRequest extends FormRequest
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
            'code' => 'required|max:2',
            'name' => 'required|string|max:255',
            'meta' => 'required',
        ];

        if ($this->isMethod('patch')) {
            $rules['code'] = 'required|max:2|unique:indonesia_provinces,code,' . $this->route('provinces');
            $rules['name'] = 'required|string|max:255|unique:indonesia_provinces,name,' . $this->route('provinces');
        }

        return $rules;
    }
}
