<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'create_user',
        'create_name',
        'update_user',
        'update_name',
        'delete_user',
        'delete_name',
    ];

    // Nếu có quan hệ với User (nếu một Role có nhiều User)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
