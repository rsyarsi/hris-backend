<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
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
            'shift_group_id' => 'required|exists:shift_groups,id',
            'code' => 'required|max:45',
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('shifts')->ignore($this->route('shift')),
            ],
            'in_time' => 'required|max:45',
            'out_time' => 'required|max:45',
            'finger_in_less' => 'required|integer|digits_between:1,11',
            'finger_in_more' => 'required|integer|digits_between:1,11',
            'finger_out_less' => 'required|integer|digits_between:1,11',
            'finger_out_more' => 'required|integer|digits_between:1,11',
            'night_shift' => 'required|integer',
            'active' => 'required|integer',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => Str::upper($this->input('name')),
            'code' => Str::upper($this->input('code')),
        ]);
    }
}
