<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
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
            'user_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'user_name')->where(function ($query) {
                    return $query->whereNull('deleted_at'); // ✅ Bỏ qua user đã soft delete
                }),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->whereNull('deleted_at'); // ✅ Bỏ qua email đã soft delete
                }),
            ],
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
