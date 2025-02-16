<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function import(): JsonResponse
    {
        return $this->locationService->fetchAndStoreLocations();
    }
}
