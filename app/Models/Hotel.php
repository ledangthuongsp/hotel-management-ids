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
}