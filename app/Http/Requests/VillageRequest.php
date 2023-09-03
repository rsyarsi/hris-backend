<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VillageRequest extends FormRequest
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
            'code' => 'required|max:10',
            'district_code' => 'required|exists:indonesia_districts,code',
            'name' => 'required|string|max:255',
            'meta' => 'required',
        ];

        if ($this->isMethod('patch')) {
            $rules['code'] = 'required|max:10|unique:indonesia_villages,code,' . $this->route('villages');
            $rules['name'] = 'required|string|max:255|unique:indonesia_villages,name,' . $this->route('villages');
        }

        return $rules;
    }
}
