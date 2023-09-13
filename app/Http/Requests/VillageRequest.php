<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
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
        return [
            'code' => [
                'required',
                'max:10',
                'string',
                Rule::unique('indonesia_villages')->ignore($this->route('village')),
            ],
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('indonesia_villages')->ignore($this->route('village')),
            ],
            'district_code' => 'required|exists:indonesia_districts,code',
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
