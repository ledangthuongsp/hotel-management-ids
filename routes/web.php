<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// Trang chủ
Route::get('/', function () {
    return view('landingpage.landingpage');
});

// Route đăng nhập
Route::get('/login', function () {
    return view('login.login');
})->name('login');

// Xử lý đăng nhập và đăng xuất
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ✅ Bảo vệ dashboard bằng middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    });
});
