<?php

namespace App\Http\Requests\RoleRequest;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles|max:255',
            'description' => 'nullable|string|max:500',
        ];
    }
}
