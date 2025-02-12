<?php
namespace App\Repositories\Eloquent;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;

class UserRepository implements IUserRepository
{
    public function getAll()
    {
        return User::with('role')->get();
    }
    public function findById($id)
    {
        return User::with('role')->findOrFail($id);
    }
    public function create(array $data)
    {
        return User::create($data);
    }
}