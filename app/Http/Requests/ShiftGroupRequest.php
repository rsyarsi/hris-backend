<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ShiftGroupRequest extends FormRequest
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
                'string',
                'max:150',
                Rule::unique('shift_groups')->ignore($this->route('shift_group')),
            ],
            'hour' => 'required|integer|digits_between:1,11',
            'day' => 'required|integer|digits_between:1,11',
            'type' => 'required|string|max:45',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => Str::upper($this->input('name')),
        ]);
    }
}
