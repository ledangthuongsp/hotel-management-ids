<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract
{
    use HasFactory, HasApiTokens, SoftDeletes, Authenticatable, Notifiable;

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

    // Scope tìm kiếm linh hoạt
    public function scopeSearch($query, $filters)
    {
        if (!empty($filters['name'])) {
            // Tìm kiếm theo tên (first_name + last_name)
            $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $filters['name'] . '%']);
        }

        if (!empty($filters['user_name'])) {
            // Tìm kiếm theo user_name
            $query->where('user_name', 'like', '%' . $filters['user_name'] . '%');
        }

        if (!empty($filters['email'])) {
            // Tìm kiếm theo email
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['role_id'])) {
            // Tìm kiếm theo role_id
            $query->where('role_id', '=', $filters['role_id']);
        }

        return $query;
    }
}
