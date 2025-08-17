<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminUser\App\Http\Controllers\AdminUserActivateController;
use Modules\AdminUser\App\Http\Controllers\AdminUserCreateController;
use Modules\AdminUser\App\Http\Controllers\AdminUserDeactivateController;
use Modules\AdminUser\App\Http\Controllers\AdminUserDestroyController;
use Modules\AdminUser\App\Http\Controllers\AdminUserIndexController;
use Modules\AdminUser\App\Http\Controllers\AdminUserShowController;
use Modules\AdminUser\App\Http\Controllers\AdminUserUpdateController;
use Modules\AdminUser\App\Http\Controllers\Auth\AdminUserChangePasswordController;
use Modules\AdminUser\App\Http\Controllers\Auth\AdminUserLoginController;
use Modules\AdminUser\App\Http\Controllers\Auth\AdminUserLogoutController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/
Route::middleware(['cors'])->group(function () {
    Route::post('login', AdminUserLoginController::class);
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin', 'can:view_admin_users'], 'prefix' => 'users'], function () {
    Route::post('/', AdminUserIndexController::class);

    Route::post('create', AdminUserCreateController::class)
        ->middleware('can:view_admin_users');

    Route::get('show/{uuid}', AdminUserShowController::class);

    Route::post('update', AdminUserUpdateController::class)
        ->middleware('can:view_admin_users');

    Route::post('destroy', AdminUserDestroyController::class)
        ->middleware('can:view_admin_users');

    Route::post('deactivate', AdminUserDeactivateController::class)
        ->middleware('can:view_admin_users');

    Route::post('activate', AdminUserActivateController::class)
        ->middleware('can:view_admin_users');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin']], function () {
    Route::post('logout', AdminUserLogoutController::class);

    Route::post('change-password', AdminUserChangePasswordController::class);
});
