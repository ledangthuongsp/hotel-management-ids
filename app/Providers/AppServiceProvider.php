<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\IHotelRepository;
use App\Repositories\Eloquent\HotelRepository;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(IHotelRepository::class, HotelRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đăng ký các Gate phân quyền cho menu
        Gate::define('view-admin-menu', function (User $user) {
            return $user->role_id == 1; // Chỉ admin mới có quyền xem menu này
        });
    }
}
