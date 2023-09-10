<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
        return [
            'code' => [
                'required',
                'max:2',
                'string',
                Rule::unique('indonesia_provinces')->ignore($this->route('provinces')),
            ],
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('indonesia_provinces')->ignore($this->route('provinces')),
            ],
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
