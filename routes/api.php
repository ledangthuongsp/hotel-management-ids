<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('/hotels/search', [HotelController::class, 'search']);

Route::get('cities', [CityController::class, 'getAllCities'])->name('cities');

// API to fetch cities, districts, and wards
Route::get('get-all-cities', [AddressController::class, 'getAllCities'])->name('get-all-cities');
Route::get('districts/{cityId}', [AddressController::class, 'getDistrictsByCityId'])->name('districts');
Route::get('wards/{districtId}', [AddressController::class, 'getWards'])->name('wards');

// Các route cần xác thực
Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('profile', [ProfileController::class, 'updateProfile']);
    Route::get('roles', [RoleController::class, 'listRoles']);
        Route::get('/roles/{id}', [RoleController::class, 'findRoleById']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{id}', [HotelController::class, 'show']);
    Route::post('/hotels', [HotelController::class, 'store']);
    Route::put('/hotels/{id}', [HotelController::class, 'update']);
    Route::delete('/hotels/{id}', [HotelController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'getUser']);
    Route::post('/users', [UserController::class, 'createUser']);
    Route::put('/users/{id}', [UserController::class, 'updateUser']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);

    Route::get('/profile', [ProfileController::class, 'getProfile']); // Lấy thông tin profile
    Route::post('/profile', [ProfileController::class, 'updateProfile']); // Cập nhật thông tin cá nhân
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']); // Cập nhật avatar riêng

    Route::post('/change-password', [PasswordController::class, 'changePassword']);
    Route::post('/users/{id}/upload-avatar', [UserController::class, 'uploadUserAvatar']);


});

// Các route yêu cầu xác thực và quyền admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/roles', [RoleController::class, 'listRoles']);
    Route::post('/roles', [RoleController::class, 'createRole']);
    Route::delete('/roles/{id}', [RoleController::class, 'deleteRole']);
    
});
Route::post('/import-locations', [LocationController::class, 'import']);
