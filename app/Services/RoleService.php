<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;

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
        if (User::where('role_id', $roleId)->exists()) {
            throw new \Exception('Không thể xóa role đang được sử dụng');
        }
        return Role::destroy($roleId);
    }
}
