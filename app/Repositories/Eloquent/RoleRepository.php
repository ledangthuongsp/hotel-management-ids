<?php
namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Interfaces\IRoleRepository;

class RoleRepository implements IRoleRepository
{
    public function getAll()
    {
        return Role::all();
    }
    public function create(array $data)
    {
        return Role::create($data);
    }
}