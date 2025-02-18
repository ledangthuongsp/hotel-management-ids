<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'name_jp',
        'code',
        'user_id',
        'city_id',
        'email',
        'telephone',
        'fax',
        'company_name',
        'tax_code',
        'address_1',
        'address_2',
    ];
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Phương thức tìm kiếm linh hoạt
    public function scopeSearch($query, $filters)
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['code'])) {
            $query->where('code', 'like', '%' . $filters['code'] . '%');
        }

        if (!empty($filters['city_id'])) {
            $query->whereHas('city', function ($q) use ($filters) {
                // So sánh trực tiếp với city_id thay vì dùng 'like'
                $q->where('id', '=', $filters['city_id']);
            });
        }

        return $query;
    }

}