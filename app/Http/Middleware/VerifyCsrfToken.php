<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Các route sẽ được bỏ qua CSRF Token
     */
    protected $except = [
        'api/seeder/create-role',  // ⚡ Bỏ qua CSRF cho API tạo Role
        'api/seeder/register',     // ⚡ Bỏ qua CSRF cho API đăng ký User
        'api/login',
    ];
}
