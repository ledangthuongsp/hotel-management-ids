<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;

// Trang chủ
Route::get('/', function () {
    return view('landingpage.landingpage');
});

// Route đăng nhập và đăng ký
Route::get('login', function () {
    return view('login.login');
})->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('register', function () {
    return view('register.register');
})->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

// Các route yêu cầu xác thực
Route::middleware(['auth:sanctum'])->group(function () {

    // Route quản lý khách sạn
    Route::get('/hotels', [HotelController::class, 'ui_index'])->name('hotels.index');
    Route::get('/hotels/{id}', [HotelController::class, 'ui_show'])->name('hotels.show');
    Route::post('/hotels', [HotelController::class, 'ui_store'])->name('hotels.store');
    Route::get('/hotels/create', [HotelController::class, 'ui_create'])->name('hotels.create');
    Route::put('/hotels/{id}/edit', [HotelController::class, 'ui_edit'])->name('hotels.edit');
});
