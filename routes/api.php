<?php

use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\FashionStyleController;
use App\Http\Controllers\Api\StylistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Banners Routes
Route::get('banners', BannerController::class);

// Stylist Routes
Route::prefix('stylists')->controller(StylistController::class)->group(function () {
    Route::get('', 'stylists');
    Route::get('trusted', 'trusted');
    Route::get('recommended-services', 'recommendedServices');
    Route::get('{stylist_id}', 'stylistDetails');
});

// Fashion Styles
Route::get('fashion-styles', FashionStyleController::class);
