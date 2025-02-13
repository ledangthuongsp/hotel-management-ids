<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('profile', [ProfileController::class, 'updateProfile']);
    Route::get('roles', [RoleController::class, 'listRoles']);
    Route::post('roles', [RoleController::class, 'createRole'])->middleware('can:manage-roles');
    Route::delete('roles/{id}', [RoleController::class, 'deleteRole'])->middleware('can:manage-roles');
});