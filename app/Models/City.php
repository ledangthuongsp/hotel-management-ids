<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes, HasFactory;

    protected $filltable = [
        'name',
        'description',
    ];
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }
}