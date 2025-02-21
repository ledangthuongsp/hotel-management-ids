<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use App\Exceptions\AppException;

class ProfileController extends Controller
{
    public function getProfile()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                throw new AppException('User not authenticated', 401, 401);
            }

            return response()->json([
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'day_of_birth' => $user->day_of_birth,
                'role_id' => $user->role_id,
                'role' => $user->role ? $user->role->name : 'N/A',
                'avatar_url' => $user->avatar_url ?? '/images/default_avatar.png',
            ], 200);
        } catch (AppException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AppException($e->getMessage(), $e->getCode(), 500);
        }
    }

    public function updateProfile(ProfileRequest $request)
    {
        try {
            Log::info('Received updateProfile request', $request->all());
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            if (!$user) {
                throw new AppException('User not authenticated', 401, 401);
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
        } catch (AppException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AppException($e->getMessage(), $e->getCode(), 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if (!$user) {
                throw new AppException('User not authenticated', 401, 401);
            }

            if ($request->hasFile('avatar')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('avatar')->getRealPath())->getSecurePath();
                $user->avatar_url = $uploadedFileUrl;
                $user->save();

                return response()->json(['avatar_url' => $uploadedFileUrl], 200);
            }

            throw new AppException('Không có file nào được tải lên', 400, 400);
        } catch (AppException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AppException($e->getMessage(), $e->getCode(), 500);
        }
    }

    public function ui_getUser()
    {
        return view('profile.index');
    }
}