<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
/**
 * @OA\Tag(
 *     name="Profile",
 *     description="Quản lý thông tin cá nhân"
 * )
 */
class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * @OA\Put(
     *     path="/profile",
     *     summary="Cập nhật thông tin cá nhân",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="avatar", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công"),
     *     @OA\Response(response=401, description="Không có quyền truy cập")
     * )
     */
    public function updateProfile(ProfileUpdateRequest $request): JsonResponse
    {
        $user = Auth::user();
        dd($user);
        $updatedUser = $this->profileService->updateProfile($user, $request->validated());

        return response()->json(['message' => 'Profile updated successfully', 'user' => $updatedUser]);
    }
}
