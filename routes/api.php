<?php

use App\Http\Controllers\Api\V1\Admin\AdvertisementController;
use App\Http\Controllers\Api\V1\Admin\AdNetworkSettingController;
use App\Http\Controllers\Api\V1\Admin\AndroidAppController;
use App\Http\Controllers\Api\V1\Admin\AppVersionController;
use App\Http\Controllers\Api\V1\Admin\AuthController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\NotificationController;
use App\Http\Controllers\Api\V1\App\AdConfigController;
use App\Http\Controllers\Api\V1\App\AppController;
use App\Http\Controllers\Api\V1\UniversalController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('universal', UniversalController::class)->middleware(['api.log', 'throttle:admin-api']);

    Route::middleware(['api.log', 'app.auth', 'throttle:app-api'])->group(function (): void {
        Route::post('install', [AppController::class, 'install']);
        Route::post('heartbeat', [AppController::class, 'heartbeat']);
        Route::post('save-token', [AppController::class, 'saveToken']);
        Route::get('ads', [AppController::class, 'ads']);
        Route::get('ad-config', AdConfigController::class);
        Route::get('version-check', [AppController::class, 'versionCheck']);
        Route::post('event', [AppController::class, 'event']);
        Route::get('notifications', [AppController::class, 'notifications']);
    });

    Route::post('admin/register', [AuthController::class, 'register'])->middleware('throttle:login');
    Route::post('admin/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::prefix('admin')->middleware(['api.log', 'throttle:admin-api'])->group(function (): void {
        Route::get('dashboard', DashboardController::class);
        Route::apiResource('apps', AndroidAppController::class);
        Route::apiResource('ad-settings', AdNetworkSettingController::class);
        Route::apiResource('advertisements', AdvertisementController::class);
        Route::apiResource('notifications', NotificationController::class);
        Route::post('notifications/{notification}/send', [NotificationController::class, 'send']);
        Route::apiResource('app-versions', AppVersionController::class);
    });
});
