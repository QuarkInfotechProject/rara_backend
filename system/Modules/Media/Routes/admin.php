<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\App\Http\Controllers\File\FileCreateController;
use Modules\Media\App\Http\Controllers\File\FileDestroyController;
use Modules\Media\App\Http\Controllers\File\FileIndexController;
use Modules\Media\App\Http\Controllers\File\FileShowController;
use Modules\Media\App\Http\Controllers\File\FileUpdateController;
use Modules\Media\App\Http\Controllers\FileCategory\FileCategoryCreateController;
use Modules\Media\App\Http\Controllers\FileCategory\FileCategoryDestroyController;
use Modules\Media\App\Http\Controllers\FileCategory\FileCategoryIndexController;
use Modules\Media\App\Http\Controllers\FileCategory\FileCategoryShowController;
use Modules\Media\App\Http\Controllers\FileCategory\FileCategoryUpdateController;
use Modules\Media\App\Http\Requests\FileCategory\FileCategoryCreateRequest;
use Modules\Media\App\Http\Controllers\Popup\ShowPopupController;
use Modules\Media\App\Http\Controllers\Popup\UpdatePopupController;
use Modules\Media\App\Http\Controllers\Popup\IndexPopupController;
use Modules\Media\App\Http\Controllers\Popup\DestroyPopupController;
use Modules\Media\App\Http\Controllers\Popup\CreatePopupController;

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

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin', 'can:view_media_center']], function () {

    Route::prefix('file-categories')->group(function() {
        Route::get('/', FileCategoryIndexController::class)
        ->middleware('can:view_media_center');

        Route::post('create', FileCategoryCreateController::class)
        ->middleware('can:create_file_category');

        Route::get('show/{slug}', FileCategoryShowController::class)
        ->middleware('can:view_media_center');

        Route::post('update', FileCategoryUpdateController::class)
        ->middleware('can:view_media_center');

        Route::post('destroy', FileCategoryDestroyController::class)
        ->middleware('can:view_media_center');
    });

    Route::prefix('files')->group(function() {
        Route::post('/', FileIndexController::class)
        ->middleware('can:view_media_center');

        Route::post('create', FileCreateController::class)
        ->middleware('can:view_media_center');

        Route::get('show/{id}', FileShowController::class)
        ->middleware('can:view_media_center');

        Route::post('update', FileUpdateController::class)
        ->middleware('can:view_media_center');

        Route::post('destroy', FileDestroyController::class)
        ->middleware('can:view_media_center');
    });

    Route::prefix('popup')->group(function() {
        Route::get('/', IndexPopupController::class)
            ->middleware('can:view_media_center');

        Route::post('create', CreatePopupController::class)
            ->middleware('can:view_media_center');

        Route::get('show/{id}', ShowPopupController::class)
            ->middleware('can:view_media_center');

        Route::post('update', UpdatePopupController::class)
            ->middleware('can:view_media_center');

        Route::post('destroy', DestroyPopupController::class)
            ->middleware('can:view_media_center');
    });
});
