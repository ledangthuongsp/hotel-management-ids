<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/test",
     *     tags={"Test"},
     *     summary="API Test Endpoint",
     *     @OA\Response(response=200, description="Test Successful")
     * )
     */
    public function test()
    {
        return response()->json(['message' => 'Test Successful'], 200);
    }
}
