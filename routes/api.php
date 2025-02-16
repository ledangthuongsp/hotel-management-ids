<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\LocationController;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

// Các route cần xác thực
Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('profile', [ProfileController::class, 'updateProfile']);
    Route::get('roles', [RoleController::class, 'listRoles']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{id}', [HotelController::class, 'show']);
    Route::post('/hotels', [HotelController::class, 'store']);
    Route::put('/hotels/{id}', [HotelController::class, 'update']);
    Route::delete('/hotels/{id}', [HotelController::class, 'destroy']);
});

// Các route yêu cầu xác thực và quyền admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('roles', [RoleController::class, 'createRole']);
    Route::delete('roles/{id}', [RoleController::class, 'deleteRole']);
    
});
Route::post('/import-locations', [LocationController::class, 'import']);
