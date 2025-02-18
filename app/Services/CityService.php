<?php
namespace App\Services;
use App\Models\City;

class CityService {
    public function getAllCity()
    {
        return City::all();
    }
}