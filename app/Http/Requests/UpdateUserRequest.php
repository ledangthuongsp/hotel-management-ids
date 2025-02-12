<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'user_name' => 'sometimes|string|max:255|unique:users,user_name,' . $this->user,
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $this->user,
            'password' => 'nullable|string|min:8|max:32',
            'day_of_birth' => 'sometimes|date',
            'role_id' => 'sometimes|integer|exists:roles,id',
        ];
    }
}
