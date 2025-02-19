<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckUserRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(CorsMiddleware::class);

        // Đăng ký alias cho middleware
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'check.role'=>CheckUserRole::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'stripe/*', // Loại trừ tất cả các route bắt đầu với 'stripe/'
            'webhook/receive', // Loại trừ route cụ thể 'webhook/receive'
            // Thêm các route khác nếu cần
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
