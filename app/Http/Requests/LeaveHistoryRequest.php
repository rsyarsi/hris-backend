<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveHistoryRequest extends FormRequest
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
            'leave_id' => 'required|exists:leaves,id',
            'user_id' => 'required|exists:users,id',
            'description' => 'required|max:255',
            'ip_address' => 'required|max:45',
            'user_agent' => 'required',
            'comment' => 'required',
        ];
    }
}
