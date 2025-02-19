<?php

namespace App\Http\Requests\RoleRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user() && Auth::user()->role->name === 'Admin';
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name|in:Admin,Member',
            'description' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Role name is required',
            'name.unique' => 'This role already exists',
            'name.in' => 'Only Admin and Member roles are allowed',
        ];
    }
}
