<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class RoleService
{
    public function listRoles()
    {
        return Role::all();
    }

    public function createRole(array $data)
    {
        // Đếm role chưa bị xóa
        if (Role::count() >= 2) {
            throw new \Exception('Chỉ có thể tạo 2 roles (Admin, Member)');
        }
    
        // Kiểm tra role trùng tên (bao gồm role đã bị soft delete)
        if (Role::withTrashed()->where('name', $data['name'])->exists()) {
            throw new \Exception('Tên role đã tồn tại, vui lòng chọn tên khác.');
        }
    
        return Role::create($data);
    }
    

    public function deleteRole($roleId)
    {
        // Kiểm tra role có tồn tại không
        $role = Role::find($roleId);
        if (!$role) {
            throw new ModelNotFoundException("Role not found.");
        }

        // Kiểm tra xem role có đang được sử dụng không
        if (User::where('role_id', $roleId)->exists()) {
            throw new \Exception('This role is assigned to existing users and cannot be deleted.');
        }

        return $role->delete();
    }

    public function findRoleById($id)
    {
        return Role::findOrFail($id);
    }
}
