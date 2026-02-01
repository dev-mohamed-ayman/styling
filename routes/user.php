<?php

use App\Http\Controllers\Api\User\AddressController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Guest Routes
Route::middleware('guest:user')->group(function () {

    // Auth Routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('send-otp', 'sendOtp');
        Route::post('verify-otp', 'verifyOtp');
    });


});


// Authenticated Routes
Route::middleware('auth:user')->group(function () {


    // Auth Routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('update', 'update');
        Route::post('logout', 'logout');
        Route::get('profile', 'profile');
    });

    // Addresses Routes
    Route::prefix('addresses')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::post('/{address}', 'update');
        Route::delete('/{address}', 'destroy');
    });

    // Reviews Routes
    Route::post('reviews', [ReviewController::class, 'review']);


});
