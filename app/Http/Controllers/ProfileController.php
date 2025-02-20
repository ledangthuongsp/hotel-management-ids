<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
class ProfileController extends Controller
{
    /**
     * Lấy thông tin hồ sơ người dùng hiện tại
     */
    public function getProfile()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            return response()->json([
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'day_of_birth' => $user->day_of_birth,
                'role_id' => $user->role_id,
                'role' => $user->role ? $user->role->name : 'N/A', // ✅ Trả về tên role
                'avatar_url' => $user->avatar_url ?? '/images/default_avatar.png',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }
    /**
     * Cập nhật thông tin hồ sơ (trừ avatar)
     */
    public function updateProfile(ProfileRequest $request)
    {
        Log::info('Received updateProfile request', $request->all()); // 🐞 Debug log
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'user_name' => $request->input('user_name'),
            'email' => $request->input('email'),
            'day_of_birth' => $request->input('day_of_birth'),
            'role_id' => $request->input('role_id')
        ]);

        return response()->json(['message' => 'Cập nhật thành công', 'user' => $user], 200);
    }


    /**
     * Cập nhật Avatar riêng biệt
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        dd($user);
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        if ($request->hasFile('avatar')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('avatar')->getRealPath())->getSecurePath();
            $user->avatar_url = $uploadedFileUrl;
            $user->save();

            return response()->json(['avatar_url' => $uploadedFileUrl], 200);
        }

        return response()->json(['error' => 'Không có file nào được tải lên'], 400);
    }

    /**
     * Trả về giao diện profile
     */
    public function ui_getUser()
    {
        return view('profile.index');
    }
}
