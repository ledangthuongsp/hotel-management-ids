<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // 🔥 IMPORT AUTH

class UserService
{
    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'message' => 'User retrieved successfully',
                'user' => $user
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function createUser(Request $request)
    {
        try {
            // Lấy thông tin admin tạo user
            $adminId = Auth::check() ? Auth::id() : null;
            $adminName = Auth::check() ? Auth::user()->user_name : 'system';

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'user_name' => $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'day_of_birth' => $request->day_of_birth ?? '1990-01-01',
                'avatar_url' => $request->avatar_url ?? 'default_avatar.png',
                'role_id' => $request->role_id,
                'create_user' => $adminId,  // Lưu ID admin tạo user
                'create_name' => $adminName // Lưu tên admin tạo user
            ]);

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $data = $request->except(['password']);
            if ($request->has('password')) {
                $data['password'] = Hash::make($request->password);
                $data['update_pass_flg'] = 1;
                $data['update_pass_date'] = Carbon::now();
            }

            // Lưu thông tin người cập nhật
            $data['update_user'] = Auth::id() ?? 1;
            $data['update_name'] = Auth::user() ? Auth::user()->user_name : 'system';

            $user->update($data);

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update([
                'deleted_at' => Carbon::now(),
                'delete_user' => Auth::id() ?? 1,
                'delete_name' => Auth::user() ? Auth::user()->user_name : 'system',
                'del_flg' => 1
            ]);

            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
