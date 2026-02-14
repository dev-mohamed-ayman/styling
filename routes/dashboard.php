<?php

use App\Http\Controllers\Dashboard\BannerController;
use App\Http\Controllers\Dashboard\FashionStyleController;
use App\Http\Controllers\Dashboard\StylistController;
use App\Http\Controllers\Dashboard\StylistReviewsController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\NotificationController;
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
    Route::resource('users', UserController::class)->middleware('permission:view users');

    Route::resource('fashion_styles', FashionStyleController::class)->middleware('permission:view fashion_styles');
    Route::resource('banners', BannerController::class)->middleware('permission:view banners');

    // Stylists - All in one CRUD (features, images, services are managed within)
    Route::resource('stylists', StylistController::class)->middleware('permission:view stylists');

    // Reviews remain separate (optional - can also be managed within stylists)
    Route::resource('stylist_reviews', StylistReviewsController::class)->middleware('permission:view stylist_reviews');

    Route::resource('admins', AdminController::class)->middleware('permission:view admins');

    // Roles & Permissions Routes
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('admin_roles', [RoleController::class, 'adminRoles'])->name('admin_roles.index');
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

});
