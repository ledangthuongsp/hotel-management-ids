<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép mọi người dùng được gửi request này
    }

    public function rules()
    {
        $userId = $this->user()->id; // Lấy ID của user hiện tại

        return [
            'first_name' => 'required|string|max:' . config('validation.name_max'),
            'last_name' => 'required|string|max:' . config('validation.name_max'),
            'user_name' => [
                'required', 'string', 'max:' . config('validation.username_max'),
                Rule::unique('users', 'user_name')->ignore($userId)
            ],
            'email' => [
                'required', 'string', 'email', 'max:' . config('validation.email_max'),
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'role_id' => 'required|integer|exists:roles,id',
            'day_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:' . config('validation.avatar_max'),
        ];
    }

    public function messages()
    {
        return trans('validation_messages'); // Gọi file `lang/en/validation_messages.php`
    }
}
