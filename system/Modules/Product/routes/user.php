<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\App\Http\Controllers\User\Product\FetchProductListController;
use Modules\Product\App\Http\Controllers\User\Product\FetchTagsAsProductController;
use Modules\Product\App\Http\Controllers\User\Product\GetProductDetailController;
use Modules\Product\App\Http\Controllers\User\Product\GetProductDetailCommentController;
use Modules\Product\App\Http\Controllers\User\Product\GetSavedProductController;
use Modules\Product\App\Http\Controllers\User\Product\ToggleSaveProductController;
use Modules\Product\App\Http\Controllers\User\Product\ListProductForHomepageController;
use Modules\Product\App\Http\Controllers\User\Product\ListExperienceTagsForHomepageController;
use Modules\Product\App\Http\Controllers\User\Product\ListReviewsAndRatingForHomepageController;
use Modules\Product\App\Http\Controllers\User\Product\SearchProductController;
use Modules\Product\App\Http\Controllers\User\Product\ListAllProductSlugController;
use Modules\Product\App\Http\Controllers\User\Product\ListProductOfDepartureController;
use Modules\Product\App\Http\Controllers\User\Product\ListTrekForHomepageController;
use Modules\Product\App\Http\Controllers\User\Product\ListTourForHomepageController;
use Modules\Product\App\Http\Controllers\User\Product\ListActivitiesForHomepageController;
use Modules\Product\App\Http\Controllers\User\Product\ListSafariForHomepageController;
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

Route::group(['middleware' => ['cors', 'json.response'], 'prefix' => 'product'], function () {
    Route::post('list', FetchProductListController::class)->middleware('optional.auth');

    Route::get('tags/{product}', FetchTagsAsProductController::class);

    Route::get('detail/{slug}', GetProductDetailController::class)->middleware('optional.auth');

    Route::post('detail/review/{slug}', GetProductDetailCommentController::class);

    Route::get('slug-list', ListAllProductSlugController::class);

    Route::get('departure/lists', ListProductOfDepartureController::class);
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:user'], 'prefix' => 'product'], function () {

    Route::get('wishlist/{productId}', ToggleSaveProductController::class);

    Route::get('wishlist', GetSavedProductController::class);
});

Route::group(['middleware' => ['cors', 'json.response'], 'prefix' => 'homepage'], function () {

    Route::get('product/{type}', ListProductForHomepageController::class);

    Route::get('experience-tag', ListExperienceTagsForHomepageController::class);

    Route::get('review', ListReviewsAndRatingForHomepageController::class);

    Route::post('search/{search}', SearchProductController::class);

    Route::get('product-list/treks', ListTrekForHomepageController::class);

    Route::get('product-list/tours', ListTourForHomepageController::class);

    Route::get('product-list/activities', ListActivitiesForHomepageController::class);

    Route::get('product-list/safaris', ListSafariForHomepageController::class);
});
