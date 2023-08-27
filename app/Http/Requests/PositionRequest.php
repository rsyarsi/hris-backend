<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'active' => 'required|integer',
        ];

        if ($this->isMethod('patch')) {
            // If it's an update request, exclude the name from being unique to the current department
            $rules['name'] = 'required|string|max:255|unique:mpositions,name,' . $this->route('mpositions');
        }

        return $rules;
    }
}
