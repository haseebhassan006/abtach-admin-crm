<?php

namespace App\Providers;

use App\Interfaces\RolesInterface;
use App\Interfaces\UsersInterface;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\PermissionsInterface;
use App\Repositories\PermissionRepository;

class AppRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(PermissionsInterface::class, PermissionRepository::class);
        $this->app->bind(RolesInterface::class, RolesRepository::class);
        $this->app->bind(UsersInterface::class, UsersRepository::class);
    }
}
