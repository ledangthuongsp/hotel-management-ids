<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user() && Auth::user()->role->name === 'Admin';
    }

    public function rules()
    {
        return [
            'description' => 'nullable|string|max:255'
        ];
    }
}
