<?php

use Illuminate\Support\Facades\Route;
use Modules\PageVault\App\Http\Controllers\User\GetWhyUsController;
use Modules\PageVault\App\Http\Controllers\User\GetPromotionController;
use Modules\PageVault\App\Http\Controllers\User\GetPagesDetailController;
use Modules\PageVault\App\Http\Controllers\User\GetFaqsListByCategoryController;
use Modules\PageVault\App\Http\Controllers\User\GetTeamListController;
use Modules\PageVault\App\Http\Controllers\User\AddCtaByTypeController;




Route::group(['middleware' => ['cors', 'json.response'], 'prefix' => 'homepage'], function () {

    Route::get('why-us', GetWhyUsController::class);

    Route::get('promotion', GetPromotionController::class);
});

Route::group(['middleware' => ['cors', 'json.response'], 'prefix' => 'page'], function () {

    Route::get('detail/{slug}', GetPagesDetailController::class);
    Route::get('faq/{slug}', GetFaqsListByCategoryController::class);
    Route::get('team', GetTeamListController::class);
    Route::post('add-cta', AddCtaByTypeController::class);

});
