<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LegalityTypeRequest extends FormRequest
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
            'name' => 'required|string|max:150',
            'active' => 'required|integer',
            'extended' => 'required|boolean',
        ];

        if ($this->isMethod('patch')) {
            $rules['name'] = 'required|string|max:150|unique:mlegalitytypes,name,' . $this->route('legality-types');
        }

        return $rules;
    }
}
