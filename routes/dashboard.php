<?php

use Illuminate\Support\Facades\Route;

// Dashboard Routes
Route::get('/', 'DashboardController@index')->name('dashboard')->middleware('auth:admin');

// Login Routes
Route::middleware('guest:admin')->controller('LoginController')->group(function () {
    Route::get('/login', 'loginView')->name('login');
    Route::post('/login', 'login')->name('login');
});


Route::middleware('auth:admin')->group(function () {
    // Profile Routes
    Route::prefix('profile')->controller('ProfileController')->group(function () {
        Route::get('/', 'profile')->name('profile');
        Route::post('/update-profile', 'updateProfile')->name('update-profile');
        Route::post('/update-password', 'updatePassword')->name('update-password');
        Route::get('logout', 'logout')->name('logout');
    });

    // User Routes
    Route::prefix('user')->name('user.')->controller('UserController')->group(function () {
        Route::get('/', 'index')->name('index');
    });
});
