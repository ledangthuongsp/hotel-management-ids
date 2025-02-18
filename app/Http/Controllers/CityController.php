<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\CityService;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    protected $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }
    public function getAllCities(): JsonResponse
    {
        $cities = $this->cityService->getAllCity();
        return response()->json($cities);
    }
}