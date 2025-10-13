<?php

use Illuminate\Support\Facades\Route;
use Modules\PageVault\App\Http\Controllers\PageVault\UpdatePageVaultController;
use Modules\PageVault\App\Http\Controllers\PageVault\GetPageDetailController;
use Modules\PageVault\App\Http\Controllers\PageVault\ListAllPageController;
use Modules\PageVault\App\Http\Controllers\OurTeam\DeleteOurTeamController;
use Modules\PageVault\App\Http\Controllers\OurTeam\AddOurTeamController;
use Modules\PageVault\App\Http\Controllers\OurTeam\DisableOurTeamController;
use Modules\PageVault\App\Http\Controllers\OurTeam\EditOurTeamController;
use Modules\PageVault\App\Http\Controllers\OurTeam\EnableOurTeamController;
use Modules\PageVault\App\Http\Controllers\OurTeam\GetDetailOurTeamController;
use Modules\PageVault\App\Http\Controllers\OurTeam\ListOurTeamController;
use Modules\PageVault\App\Http\Controllers\Faq\AddFaqController;
use Modules\PageVault\App\Http\Controllers\Faq\DeleteFaqController;
use Modules\PageVault\App\Http\Controllers\Faq\EditFaqController;
use Modules\PageVault\App\Http\Controllers\Faq\GetFaqDetailController;
use Modules\PageVault\App\Http\Controllers\Faq\PaginateFaqController;
use Modules\PageVault\App\Http\Controllers\Cta\AddCtaController;
use Modules\PageVault\App\Http\Controllers\Cta\ChangeCtaStatusController;
use Modules\PageVault\App\Http\Controllers\Cta\DeleteCtaController;
use Modules\PageVault\App\Http\Controllers\Cta\GetCtaDetailController;
use Modules\PageVault\App\Http\Controllers\Cta\PaginateCtaByCategoryController;
use Modules\PageVault\App\Http\Controllers\WhyUs\CreateWhyUsController;
use Modules\PageVault\App\Http\Controllers\WhyUs\GetDetailWhyUsController;
use Modules\PageVault\App\Http\Controllers\WhyUs\ListWhyUsController;
use Modules\PageVault\App\Http\Controllers\WhyUs\UpdateWhyUsController;
use Modules\PageVault\App\Http\Controllers\Promotion\CreatePromotionController;
use Modules\PageVault\App\Http\Controllers\Promotion\GetPromotionDetailController;
use Modules\PageVault\App\Http\Controllers\Promotion\ListAllPromotionController;
use Modules\PageVault\App\Http\Controllers\Promotion\UpdatePromotionController;
use Modules\PageVault\App\Http\Controllers\Dashboard\GetCtaStatsController;
use Modules\PageVault\App\Http\Controllers\CarRental\AddCarRentalController;
use Modules\PageVault\App\Http\Controllers\CarRental\ChangeCarRentalStatusController;
use Modules\PageVault\App\Http\Controllers\CarRental\DeleteCarRentalController;
use Modules\PageVault\App\Http\Controllers\CarRental\GetCarRentalDetailController;
use Modules\PageVault\App\Http\Controllers\CarRental\PaginateCarRentalController;
use Modules\PageVault\App\Http\Controllers\CarRental\GetCarRentalForUpdateDetailController;
use Modules\PageVault\App\Http\Controllers\CarRental\UpdateCarRentalController;



Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'page'], function () {

    Route::post('update', UpdatePageVaultController::class)
        ->middleware('can:view_pages');
    Route::get('detail/{type}', GetPageDetailController::class)
        ->middleware('can:view_pages');
    Route::get('list', ListAllPageController::class)
        ->middleware('can:view_pages');
});


Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'our-team'], function () {
    Route::post('add', AddOurTeamController::class)
        ->middleware('can:view_our_team');
    Route::get('delete/{type}', DeleteOurTeamController::class)
        ->middleware('can:view_our_team');
    Route::get('disable/{id}', DisableOurTeamController::class)
        ->middleware('can:view_our_team');
    Route::post('edit', EditOurTeamController::class)
        ->middleware('can:view_our_team');
    Route::get('enable/{id}', EnableOurTeamController::class)
        ->middleware('can:view_our_team');
    Route::get('detail/{id}', GetDetailOurTeamController::class)
        ->middleware('can:view_our_team');
    Route::get('list', ListOurTeamController::class)
        ->middleware('can:view_our_team');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'faqs'], function () {
    Route::post('add', AddFaqController::class)
        ->middleware('can:view_faqs');
    Route::post('edit', EditFaqController::class)
        ->middleware('can:view_faqs');
    Route::get('detail/{id}', GetFaqDetailController::class)
        ->middleware('can:view_faqs');
    Route::post('paginate', PaginateFaqController::class)
        ->middleware('can:view_faqs');
    Route::get('delete/{id}', DeleteFaqController::class)
        ->middleware('can:view_faqs');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'cta'], function () {
    Route::post('add', AddCtaController::class)
        ->middleware('can:view_cta');
    Route::post('change-status', ChangeCtaStatusController::class)
        ->middleware('can:view_cta');
    Route::get('delete/{id}', DeleteCtaController::class)
        ->middleware('can:view_cta');
    Route::get('detail/{id}', GetCtaDetailController::class)
        ->middleware('can:view_cta');
    Route::post('paginate', PaginateCtaByCategoryController::class)
        ->middleware('can:view_cta');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'car-rental'], function () {
//    Route::post('add', AddCarRentalController::class)
//        ->middleware('can:view_booking');
    Route::post('change-status', ChangeCarRentalStatusController::class)
        ->middleware('can:view_booking');
    Route::get('delete/{id}', DeleteCarRentalController::class)
        ->middleware('can:view_booking');
    Route::get('detail/{id}', GetCarRentalDetailController::class)
        ->middleware('can:view_booking');
    Route::post('paginate', PaginateCarRentalController::class)
        ->middleware('can:view_booking');

    Route::get('update/detail/{id}', GetCarRentalForUpdateDetailController::class)
        ->middleware('can:view_booking');

    Route::post('update', UpdateCarRentalController::class)
        ->middleware('can:view_booking');

});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'why-us'], function () {

    Route::post('create', CreateWhyUsController::class)
        ->middleware('can:view_cta');

    Route::get('detail/{id}', GetDetailWhyUsController::class)
        ->middleware('can:view_cta');

    Route::get('list', ListWhyUsController::class)
        ->middleware('can:view_cta');

    Route::post('update', UpdateWhyUsController::class)
        ->middleware('can:view_cta');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'promotion'], function () {

    Route::post('create', CreatePromotionController::class)
        ->middleware('can:view_promotion');

    Route::get('detail/{id}', GetPromotionDetailController::class)
        ->middleware('can:view_promotion');

    Route::get('list', ListAllPromotionController::class)
        ->middleware('can:view_promotion');

    Route::post('update', UpdatePromotionController::class)
        ->middleware('can:view_promotion');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'dashboard'], function () {

    Route::get('cta-stats', GetCtaStatsController::class);
});
