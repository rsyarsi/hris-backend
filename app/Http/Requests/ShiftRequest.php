<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
            'code' => 'nullable|max:45',
            'name' => 'nullable|string|max:150',
            'in_time' => 'nullable|max:45',
            'out_time' => 'nullable|max:45',
            'finger_in_less' => 'nullable|integer|digits_between:1,11',
            'finger_in_more' => 'nullable|integer|digits_between:1,11',
            'finger_out_less' => 'nullable|integer|digits_between:1,11',
            'finger_out_more' => 'nullable|integer|digits_between:1,11',
            'night_shift' => 'nullable|integer',
            'active' => 'nullable|integer',
            'libur' => 'nullable|integer',
            'on_call' => 'nullable|integer',
            'lepas_dinas' => 'nullable|in:0,1',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => Str::upper($this->input('name')),
            'code' => Str::upper($this->input('code')),
        ]);
    }

    protected function failedValidation($validator)
    {
        $response = [
            'message' => 'Validation error',
            'error' => true,
            'code' => 422,
            'data' => $validator->errors(),
        ];

        throw new ValidationException(
            $validator,
            response()->json($response, 422)
        );
    }
}
