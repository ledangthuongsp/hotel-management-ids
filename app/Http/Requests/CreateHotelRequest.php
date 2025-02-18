<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateHotelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Bạn có thể tùy chỉnh quyền truy cập ở đây, ví dụ chỉ cho phép admin hoặc người dùng có quyền
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:hotels',
            'code' => 'required|string|max:50|unique:hotels',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:50',
            'address_1' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',  // Kiểm tra xem city_id có tồn tại trong bảng cities không
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Tên khách sạn là bắt buộc.',
            'code.required' => 'Mã khách sạn là bắt buộc.',
            'email.required' => 'Email khách sạn là bắt buộc.',
            'telephone.required' => 'Số điện thoại khách sạn là bắt buộc.',
            'address_1.required' => 'Địa chỉ khách sạn dòng 1 là bắt buộc.',
            'city_id.required' => 'Thành phố khách sạn phải được chọn.',
        ];
    }
}
