<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthRequest\ChangePasswordRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Password",
 *     description="API liên quan đến đổi mật khẩu người dùng"
 * )
 */
class PasswordController extends Controller
{
    /**
     * Đổi mật khẩu người dùng
     *
     * @OA\Post(
     *     path="/change-password",
     *     summary="Đổi mật khẩu",
     *     tags={"Password"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"old_password", "new_password", "new_password_confirmation"},
     *             @OA\Property(property="old_password", type="string", example="Password123!"),
     *             @OA\Property(property="new_password", type="string", format="password", example="NewPass@123"),
     *             @OA\Property(property="new_password_confirmation", type="string", format="password", example="NewPass@123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Đổi mật khẩu thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đổi mật khẩu thành công")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Mật khẩu cũ không đúng",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mật khẩu cũ không đúng")
     *         )
     *     ),
     *     @OA\Response(response=401, description="User chưa đăng nhập",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not authenticated")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Lỗi server",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if (!$user) {
                Log::error('User not authenticated');
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            Log::info("Checking old password for user: " . $user->id);

            // Kiểm tra mật khẩu cũ
            if (!Hash::check($request->old_password, $user->password)) {
                Log::error("Incorrect old password for user: " . $user->id);
                return response()->json(['error' => 'Mật khẩu cũ không đúng'], 400);
            }

            // Cập nhật mật khẩu mới
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['message' => 'Đổi mật khẩu thành công'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }

}
