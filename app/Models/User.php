<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'day_of_birth',
        'avatar_url',
        'last_login_at',
        'update_pass_date',
        'update_pass_flg',
        'role_id',
        'create_user',
        'create_name',
        'update_user',
        'update_name',
    ];

    // Quan hệ với Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
