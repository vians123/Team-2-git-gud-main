<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Passport\UserRepository as CustomUserRepository;
use Laravel\Passport\Bridge\UserRepository as PassportUserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add custom user repository
        $this->app->bind(PassportUserRepository::class, CustomUserRepository::class);
    }
}
