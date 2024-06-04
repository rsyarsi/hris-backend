<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
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
            'first_name' => 'required',
            'relationship_id' => 'required',
            'sex_id' => 'required',
            'name' => 'required',
            'phone_number' => 'required',
            'file_cv' => 'required|file|mimes:pdf'
        ];
    }
}
