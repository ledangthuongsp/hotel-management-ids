<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API liên quan đến đăng nhập và xác thực"
 * )
 */
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Đăng nhập vào hệ thống",
     *     description="Người dùng nhập email và mật khẩu để lấy access token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="ledangthuongsp@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đăng nhập thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string", example="Bearer abc123...")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Sai email hoặc mật khẩu"),
     *     @OA\Response(response=422, description="Validation lỗi")
     * )
     */
    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());
        
        Auth::login($data['user']);
        // Nếu request từ API (có header 'Accept: application/json') -> Trả về JSON
        if ($request->expectsJson()) {
            return response()->json($data);
        }

        // Nếu request từ Blade frontend -> Redirect về dashboard
        return redirect()->route('dashboard');
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Đăng ký tài khoản",
     *     description="Người dùng nhập thông tin để đăng ký tài khoản mới",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "user_name", "email", "password"},
     *             @OA\Property(property="first_name", type="string", example="Thuong"),
     *             @OA\Property(property="last_name", type="string", example="Le"),
     *             @OA\Property(property="user_name", type="string", example="thuongle"),
     *             @OA\Property(property="email", type="string", example="ledangthuongsp@gmail.com"),
     *             @OA\Property(property="password", type="string", example="Password123!"),
     *             @OA\Property(property="role_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Đăng ký thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation lỗi"),
     *     @OA\Response(response=400, description="Lỗi khi đăng ký")
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $data
        ], 201);
    }

}
