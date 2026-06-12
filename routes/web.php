<?php

use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\AdNetworkSettingController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ApiLogController;
use App\Http\Controllers\Admin\AppController;
use App\Http\Controllers\Admin\AppVersionController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'login'])->middleware('guest')->name('login');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.store');
        Route::get('register', [AuthController::class, 'register'])->name('register');
        Route::post('register', [AuthController::class, 'storeRegister'])->name('register.store');
        Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
        Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
        Route::post('reset-password', [AuthController::class, 'updateResetPassword'])->name('password.update.reset');
    });

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');
        Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::get('change-password', [AuthController::class, 'changePassword'])->name('password.change');
        Route::put('change-password', [AuthController::class, 'updatePassword'])->name('password.update');

        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('apps/export', [AppController::class, 'export'])->name('apps.export');
        Route::patch('apps/{app}/status/{status}', [AppController::class, 'status'])->name('apps.status');
        Route::resource('apps', AppController::class)->except(['show']);

        Route::resource('advertisements', AdvertisementController::class)->except(['show']);
        Route::resource('ad-settings', AdNetworkSettingController::class)->except(['show'])->parameters(['ad-settings' => 'adSetting']);
        Route::resource('notifications', NotificationController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::post('notifications/{notification}/send', [NotificationController::class, 'send'])->name('notifications.send');
        Route::get('notifications/{notification}/logs', [NotificationController::class, 'logs'])->name('notifications.logs');
        Route::resource('versions', AppVersionController::class)->except(['show']);

        Route::get('analytics/installations', [AnalyticsController::class, 'installations'])->name('analytics.installations');
        Route::get('analytics/installations/export', [AnalyticsController::class, 'exportInstallations'])->name('analytics.installations.export');
        Route::get('analytics/active-users', [AnalyticsController::class, 'activeUsers'])->name('analytics.active-users');
        Route::get('analytics/events', [AnalyticsController::class, 'events'])->name('analytics.events');
        Route::get('api-logs', [ApiLogController::class, 'index'])->name('api-logs.index');
    });
});

Route::get('reset-password/{token}', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
