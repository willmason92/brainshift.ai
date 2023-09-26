<?php

namespace App\Providers;

use App\Contracts\Adb2cServiceInterface;
use App\Contracts\GeoRepositoryInterface;
use App\Contracts\PermissionsRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\GeoRepository;
use App\Repositories\PermissionsRepository;
use App\Repositories\UserRepository;
use App\Services\Adb2cService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(Adb2cServiceInterface::class, Adb2cService::class);
        $this->app->bind(PermissionsRepositoryInterface::class, PermissionsRepository::class);
        $this->app->bind(GeoRepositoryInterface::class, GeoRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
    }
}
