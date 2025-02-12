<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeederController;
Route::get('/', function () {
    return view('landingpage/landingpage');
});

Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/api/logout', [AuthController::class, 'logout']);


Route::middleware('web')->group(function () {
    Route::get('/api/users/{id}', [UserController::class, 'getUser']);
    Route::post('/api/users', [UserController::class, 'createUser']);
    Route::put('/api/users/{id}', [UserController::class, 'updateUser']);
    Route::delete('/api/users/{id}', [UserController::class, 'deleteUser']);
});
Route::middleware(['web'])->group(function () {
    Route::get('/api/roles', [RoleController::class, 'getAllRoles']);
    Route::post('/api/roles', [RoleController::class, 'createRole']);
    Route::put('/api/roles/{id}', [RoleController::class, 'updateRole']);
    Route::delete('/api/roles/{id}', [RoleController::class, 'deleteRole']);
});
Route::get('/api/test', [TestController::class, 'test']);
Route::post('/api/seeder/register', [SeederController::class, 'registerUser']);
Route::post('/api/seeder/create-role', [SeederController::class, 'createAdminRole']);