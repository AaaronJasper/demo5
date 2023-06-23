<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            //表單驗證
            'name' => 'required|string|max:16',
            'email' => 'required|email',
            'password' => 'required|min:4|max:16'
        ];
    }
}
