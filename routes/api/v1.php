<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeOnboardingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.token')
    ->prefix('v1')
    ->group(function () {
        Route::apiResource('user', UserController::class);
    });

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware(['auth.token', 'admin'])->group(function () {
        Route::post('/onboardings', [EmployeeOnboardingController::class, 'store']);
        Route::get('/onboardings', [EmployeeOnboardingController::class, 'index']);
        Route::get('/onboardings/{onboarding}', [EmployeeOnboardingController::class, 'show']);

        Route::put('/onboardings/{onboarding}/step-2', [EmployeeOnboardingController::class, 'updateStep2']);
        Route::put('/onboardings/{onboarding}/step-3', [EmployeeOnboardingController::class, 'updateStep3']);
        // Route::put('/onboardings/{onboarding}/step-4', [EmployeeOnboardingController::class, 'updateStep4']);

        Route::post('/onboardings/{onboarding}/submit', [EmployeeOnboardingController::class, 'submit']);
    });
});
