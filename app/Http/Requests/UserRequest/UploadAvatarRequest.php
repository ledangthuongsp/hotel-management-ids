<?php
namespace App\Http\Requests\UserRequest;
use Illuminate\Foundation\Http\FormRequest;
class UploadAvatarRequest extends FormRequest
{
    public function rules()
    {
        return [
            'avatar' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
}