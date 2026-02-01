<?php

use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\StylistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Banners Routes
Route::get('banners', BannerController::class);

// Stylist Routes
Route::prefix('stylists')->controller(StylistController::class)->group(function () {
    Route::get('trusted', 'trusted');
});
