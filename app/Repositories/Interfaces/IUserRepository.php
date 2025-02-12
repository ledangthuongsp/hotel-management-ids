<?php
namespace App\Repositories\Interfaces;
interface IUserRepository
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
}