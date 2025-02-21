<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép mọi người dùng được gửi request này
    }

    public function rules(): array
    {
        $userId = optional($this->user())->id; // Sử dụng optional() để tránh lỗi nếu user chưa đăng nhập

        return [
            'first_name' => 'required|string|max:' . config('validation.name_max'),
            'last_name' => 'required|string|max:' . config('validation.name_max'),
            'user_name' => [
                'required',
                'string',
                'max:' . config('validation.username_max'),
                Rule::unique('users', 'user_name')
                    ->ignore($userId) // Bỏ qua user hiện tại
                    ->whereNull('deleted_at'), // Bỏ qua user đã soft delete
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:' . config('validation.email_max'),
                Rule::unique('users', 'email')
                    ->ignore($userId) // Bỏ qua user hiện tại
                    ->whereNull('deleted_at'), // Bỏ qua user đã soft delete
            ],
            'role_id' => 'required|integer|exists:roles,id',
            'day_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:' . config('validation.avatar_max'),
            'password' => [
                'nullable',
                'string',
                'min:' . config('validation.password_min'),
                'max:' . config('validation.password_max'),
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
            ],
        ];
    }

    public function messages(): array
    {
        return trans('validation_messages'); // Sử dụng file ngôn ngữ để trả về thông báo lỗi
    }
}