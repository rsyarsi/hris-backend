<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
            'code' => [
                'required',
                'max:10',
                'string',
                Rule::unique('indonesia_villages')->ignore($this->route('villages')),
            ],
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('indonesia_villages')->ignore($this->route('villages')),
            ],
            'district_code' => 'required|exists:indonesia_districts,code',
            'meta' => 'required',
        ];
        return $rules;
    }
}
