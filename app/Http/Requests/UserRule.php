<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRule extends FormRequest
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
            'name' => 'required',
            'lastName' => 'required',
            'years' => 'numeric|required',
            'email' => 'required|email',
            'image' => 'required',
            'documentType' => 'required',
            'role' => 'required'
        ];
    }
}
