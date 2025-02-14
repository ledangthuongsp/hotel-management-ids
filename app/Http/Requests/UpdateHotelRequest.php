<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:hotels,name,' . $this->route('id'),
            'code' => 'required|string|max:50|unique:hotels,code,' . $this->route('id'),
            'city_id' => 'required|exists:cities,id',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên khách sạn là bắt buộc.',
            'code.unique' => 'Mã khách sạn này đã tồn tại.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'telephone.required' => 'Số điện thoại là bắt buộc.',
        ];
    }
}
