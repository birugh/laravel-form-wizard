<?php

namespace App\Providers;

use App\Models\EmployeeOnboarding;
use App\Policies\EmployeeOnboardingPolicy;
// use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $policies = [
        EmployeeOnboarding::class => EmployeeOnboardingPolicy::class,
    ];
    // public function register(): void
    // {

    // }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
