<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogFingerTempRequest extends FormRequest
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
            'nopin' => 'required|max:20',
            'datetime' => 'required|date',
            'function' => 'required|integer',
        ];
    }
}
