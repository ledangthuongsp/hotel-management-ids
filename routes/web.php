<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\UserController;

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

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/profile', [ProfileController::class, 'ui_getUser'])->name('profile.index');
    Route::get('/change-password', function () {
        return view('password.index');
    })->name('password.index');
});
// Các route yêu cầu xác thực
Route::middleware(['auth:sanctum', 'check.role'])->group(function () {
    // Route quản lý khách sạn
    Route::get('/hotels', [HotelController::class, 'ui_index'])->name('hotels.index');
    Route::get('/hotels/{id}', [HotelController::class, 'ui_show'])->name('hotels.show');
    Route::post('/hotels', [HotelController::class, 'ui_store'])->name('hotels.store');
    Route::get('/hotels/create', [HotelController::class, 'ui_create'])->name('hotels.create');
    Route::put('/hotels/{id}/edit', [HotelController::class, 'ui_edit'])->name('hotels.edit');

    //Route quản lý user
    Route::get('/users', [UserController::class, 'ui_index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'ui_show'])->name('users.show');
    Route::post('/users', [UserController::class, 'ui_store'])->name('users.store');
    Route::get('/users/create', [UserController::class, 'ui_create'])->name('users.create');
    Route::put('/users/{id}/edit', [UserController::class, 'ui_edit'])->name('users.edit');
});
Route::middleware(['auth:sanctum', 'admin'])->group(function(){
    Route::get('/roles', [RoleController::class, 'ui_index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'ui_create'])->name('roles.create'); // Đảm bảo có dòng này
    Route::post('/roles', [RoleController::class, 'createRole'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'ui_edit'])->name('roles.edit');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
});
