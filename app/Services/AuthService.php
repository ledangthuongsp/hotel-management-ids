<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Xử lý đăng nhập
     */
    public function login(array $data)
    {
        // Kiểm tra nếu email có trong database
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            // Nếu email không tồn tại, báo lỗi không tìm thấy tài khoản
            throw ValidationException::withMessages(['email' => ['Email này không tồn tại.']]);
        }

        // Kiểm tra mật khẩu nếu email tồn tại
        if (!Hash::check($data['password'], $user->password)) {
            // Nếu mật khẩu sai, báo lỗi mật khẩu sai
            throw ValidationException::withMessages(['password' => ['Mật khẩu không chính xác.']]);
        }

        // Cập nhật last_login_at nếu đăng nhập thành công
        $user->update(['last_login_at' => now()]);

        // Trả về token và thông tin người dùng
        return [
            'id' => $user->id,
            'token' => $user->createToken('API Token')->plainTextToken
        ];
    }

    /**
     * Xử lý đăng ký tài khoản
     */
    public function register(array $data)
    {
        // Mã hóa mật khẩu trước khi lưu
        $data['password'] = Hash::make($data['password']);

        // Tạo người dùng mới
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role_id'],
            'avatar_url' => 'default_avatar.png', // Gán avatar mặc định nếu không có
        ]);

        return $user;
    }
}
