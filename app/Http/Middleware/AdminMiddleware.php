<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            Log::error('Middleware: User is not authenticated.');
            return response()->json(['message' => 'Bạn cần phải đăng nhập'], 401);
        }

        Log::info('Middleware: User role_id = ' . $user->role_id);

        if ($user->role_id !== 1) {
            Log::error('Middleware: User does not have admin rights.');
            return response()->json(['message' => 'Bạn không có quyền truy cập'], 403);
        }

        return $next($request);
    }
}

