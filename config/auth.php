<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Đây là guard và broker mặc định của Laravel. Để sử dụng API với JWT,
    | chúng ta đặt mặc định guard là 'api' thay vì 'web'.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'api'), // Đổi từ 'web' thành 'api'
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Laravel hỗ trợ nhiều loại authentication guards. Ở đây, chúng ta thêm
    | guard 'api' với driver 'jwt' thay vì mặc định là 'session'.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'jwt', // Dùng JWT cho API Authentication
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Xác định cách lấy thông tin user từ database. Ở đây dùng Eloquent Model.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Không cần env() ở đây
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Cấu hình chức năng reset mật khẩu.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Thời gian timeout khi yêu cầu xác nhận mật khẩu lại.
    |
    */

    'password_timeout' => 10800,

];
