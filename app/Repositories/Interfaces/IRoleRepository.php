<?php
namespace App\Repositories\Interfaces;
interface IRoleRepository
{
    public function getAll();
    public function create(array $data);
}