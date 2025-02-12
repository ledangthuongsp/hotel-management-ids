<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class RoleService
{
    public function getAllRoles()
    {
        return Role::all();
    }

    public function createRole(Request $request)
    {
        try {
            // Kiểm tra nếu đã đủ 2 roles thì không cho tạo mới
            if (Role::count() >= 2) {
                return response()->json(['message' => 'Cannot add more roles. Only Admin and Member are allowed.'], 400);
            }

            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description,
                'create_user' => Auth::id(),
                'create_name' => Auth::user()->user_name
            ]);

            return response()->json([
                'message' => 'Role created successfully',
                'role' => $role
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating role', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateRole(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->update([
                'description' => $request->description,
                'update_user' => Auth::id(),
                'update_name' => Auth::user()->user_name
            ]);

            return response()->json([
                'message' => 'Role updated successfully',
                'role' => $role
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating role', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteRole($id)
    {
        try {
            $role = Role::findOrFail($id);

            // Kiểm tra xem role có đang được sử dụng trong bảng Users hoặc Hotel không
            if (User::where('role_id', $id)->exists()) {
                return response()->json(['message' => 'Cannot delete role. It is assigned to users.'], 400);
            }

            $role->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting role', 'error' => $e->getMessage()], 500);
        }
    }
}
