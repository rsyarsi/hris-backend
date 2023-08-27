<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
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
        ];

        if ($this->isMethod('patch')) {
            $rules['name'] = 'required|string|max:150|unique:munits,name,' . $this->route('units');
        }

        return $rules;
    }
}
