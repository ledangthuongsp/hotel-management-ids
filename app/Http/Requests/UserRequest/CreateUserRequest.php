<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép mọi request
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users|regex:/@gmail\.com$/',
            'password' => [
                'required', 'string', 'min:8', 'max:32',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'
            ],
            'day_of_birth' => 'required|date',
            'role_id' => 'required|integer|exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'email.regex' => 'Email must end with "@gmail.com".',
            'password.regex' => 'Password must contain uppercase, number, and special character.',
        ];
    }
}
