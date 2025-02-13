<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',    // Ít nhất một chữ thường
                'regex:/[A-Z]/',    // Ít nhất một chữ hoa
                'regex:/[0-9]/',    // Ít nhất một số
                'regex:/[@$!%*?&]/' // Ít nhất một ký tự đặc biệt
            ],
            'role_id' => 'required|exists:roles,id'
        ];
    }

    public function messages(): array
    {
        return [
            'email.regex' => 'Email phải có đuôi @gmail.com.',
            'password.regex' => 'Password phải có ít nhất 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
        ];
    }
}
