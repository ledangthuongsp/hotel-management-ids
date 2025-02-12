<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'user_name', 'email', 'password',
        'day_of_birth', 'avatar_url', 'last_login_at', 'update_pass_date',
        'update_pass_flg', 'delete_user', 'delete_name', 'del_flg', 'role_id',
        'create_user', 'create_name', 'update_user', 'update_name'
    ];

    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'day_of_birth' => 'datetime',
        'last_login_at' => 'datetime',
        'update_pass_date' => 'datetime',
        'update_pass_flg' => 'boolean',
        'del_flg' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // JWT functions
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
