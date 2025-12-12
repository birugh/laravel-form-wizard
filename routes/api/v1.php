<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.token')
    ->prefix('v1')
    ->group(function () {
        Route::apiResource('user', UserController::class);
    });

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/onboardings', function() {
        
    });
    Route::post('/onboardings/{id}/step-2', function() {

    });
    Route::post('/onboardings/{id}/step-3', function() {

    });
    Route::post('/onboardings/{id}/step-4', function() {

    });
});
