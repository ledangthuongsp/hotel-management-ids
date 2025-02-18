<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Ward;
class AddressController extends Controller
{
    public function __construct()
    {
        
    }
    public function index()
    {

    }
    public function getAllCities()
    {
        $cities = City::all();
        return response()->json($cities);
    }
    public function getDistrictsByCityId($cityId)
    {
        $districts = District::where('city_id', $cityId)->get();
        return response()->json($districts);
    }
    public function getWards($districtId)
    {
        $wards=Ward::where('district_id', $districtId)->get();
        return response()->json($wards);
    }
}