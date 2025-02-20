<?php

namespace App\Http\Requests\AuthRequest;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép mọi user đã đăng nhập có thể đổi mật khẩu
    }

    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => [
                'required', 'string', 'min:8', 'max:32',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
                'confirmed'
            ],
        ];
    }

    public function messages()
    {
        return [
            'new_password.regex' => 'Mật khẩu mới phải chứa ít nhất 1 chữ hoa, 1 số và 1 ký tự đặc biệt.',
            'new_password.confirmed' => 'Mật khẩu mới không khớp với xác nhận.',
        ];
    }
}
