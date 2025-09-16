<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\App\Http\Controllers\Amenity\CreateAmenityController;
use Modules\Product\App\Http\Controllers\Amenity\DeleteAmenityController;
use Modules\Product\App\Http\Controllers\Amenity\GetAmenityDetailForUpdateController;
use Modules\Product\App\Http\Controllers\Amenity\ListAmenityController;
use Modules\Product\App\Http\Controllers\Amenity\UpdateAmenityController;
use Modules\Product\App\Http\Controllers\Manager\CreateManagerController;
use Modules\Product\App\Http\Controllers\Manager\DeleteManagerController;
use Modules\Product\App\Http\Controllers\Manager\GetManagerDetailForUpdateController;
use Modules\Product\App\Http\Controllers\Manager\ListManagerController;
use Modules\Product\App\Http\Controllers\Manager\UpdateManagerController;
use Modules\Product\App\Http\Controllers\Product\Experience\CreateExperienceController;
use Modules\Product\App\Http\Controllers\Product\Experience\FetchExperienceDetailController;
use Modules\Product\App\Http\Controllers\Product\Experience\PaginateExperienceController;
use Modules\Product\App\Http\Controllers\Product\Homestay\CreateHomestayController;
use Modules\Product\App\Http\Controllers\Product\Homestay\FetchHomestayDetailController;
use Modules\Product\App\Http\Controllers\Product\Homestay\PaginateHomestayController;
use Modules\Product\App\Http\Controllers\Product\Homestay\UpdateHomestayController;
use Modules\Product\App\Http\Controllers\Product\ListAmenityForSelectController;
use Modules\Product\App\Http\Controllers\Product\ListManagerForSelectController;
use Modules\Product\App\Http\Controllers\Product\ListRelatedBlogForSelectController;
use Modules\Product\App\Http\Controllers\Product\ListRelatedProductForSelectController;
use Modules\Product\App\Http\Controllers\Product\ListTagForSelectController;
use Modules\Product\App\Http\Controllers\Tag\CreateTagController;
use Modules\Product\App\Http\Controllers\Tag\DeleteTagController;
use Modules\Product\App\Http\Controllers\Tag\GetTagDetailForUpdateController;
use Modules\Product\App\Http\Controllers\Tag\ListTagController;
use Modules\Product\App\Http\Controllers\Tag\UpdateTagController;
use Modules\Product\App\Http\Controllers\Product\Experience\UpdateExperienceController;
use Modules\Product\App\Http\Controllers\Product\Circuit\CreateCircuitController;
use Modules\Product\App\Http\Controllers\Product\Circuit\FetchCircuitDetailController;
use Modules\Product\App\Http\Controllers\Product\Circuit\PaginateCircuitController;
use Modules\Product\App\Http\Controllers\Product\Circuit\UpdateCircuitController;
use Modules\Product\App\Http\Controllers\Product\Package\CreatePackageController;
use Modules\Product\App\Http\Controllers\Product\Package\UpdatePackageController;
use Modules\Product\App\Http\Controllers\Product\Package\PaginatePackageController;
use Modules\Product\App\Http\Controllers\Product\Package\GetPackageDetailController;
use Modules\Product\App\Http\Controllers\Dashboard\GetProductRatingStatsController;
use Modules\Product\App\Http\Controllers\Product\Tour\CreateTourController;
use Modules\Product\App\Http\Controllers\Product\Tour\FetchTourDetailController;
use Modules\Product\App\Http\Controllers\Product\Tour\PaginateTourController;
use Modules\Product\App\Http\Controllers\Product\Tour\UpdateTourController;
use Modules\Product\App\Http\Controllers\Product\Activities\CreateActivitiesController;
use Modules\Product\App\Http\Controllers\Product\Activities\FetchActivitiesDetailController;
use Modules\Product\App\Http\Controllers\Product\Activities\PaginateActivitiesController;
use Modules\Product\App\Http\Controllers\Product\Activities\UpdateActivitiesController;


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

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'amenity'], function () {
    Route::post('create', CreateAmenityController::class)
        ->middleware('can:view_product');

    Route::post('delete', DeleteAmenityController::class)
        ->middleware('can:view_product');

    Route::get('detail/{id}', GetAmenityDetailForUpdateController::class)
        ->middleware('can:view_product');

    Route::post('update', UpdateAmenityController::class)
        ->middleware('can:view_product');

    Route::post('list', ListAmenityController::class)
        ->middleware('can:view_product');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'manager'], function () {
    Route::post('create', CreateManagerController::class)
        ->middleware('can:view_product');

    Route::post('delete', DeleteManagerController::class)
        ->middleware('can:view_product');

    Route::get('detail/{id}', GetManagerDetailForUpdateController::class)
        ->middleware('can:view_product');

    Route::post('update', UpdateManagerController::class)
        ->middleware('can:view_product');

    Route::get('list', ListManagerController::class)
        ->middleware('can:view_product');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'tag'], function () {

    Route::post('create', CreateTagController::class)
        ->middleware('can:view_product');

    Route::post('delete', DeleteTagController::class)
        ->middleware('can:view_product');

    Route::get('detail/{id}', GetTagDetailForUpdateController::class)
        ->middleware('can:view_product');

    Route::post('update', UpdateTagController::class)
        ->middleware('can:view_product');

    Route::post('list', ListTagController::class)
        ->middleware('can:view_product');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'dashboard'], function () {

    Route::get('product-rating-stats', GetProductRatingStatsController::class);
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'product'], function () {

    Route::post('homestay/create', CreateHomestayController::class)
        ->middleware('can:view_product');

    Route::post('homestay/update', UpdateHomestayController::class)
        ->middleware('can:view_product');

    Route::post('homestay/paginate', PaginateHomestayController::class)
        ->middleware('can:view_product');

    Route::get('homestay/detail/{id}', FetchHomestayDetailController::class)
        ->middleware('can:view_product');

    Route::post('amenity-list', ListAmenityForSelectController::class)
        ->middleware('can:view_product');

    Route::get('manager-list', ListManagerForSelectController::class)
        ->middleware('can:view_product');

    Route::get('blog-list', ListRelatedBlogForSelectController::class)
        ->middleware('can:view_product');

    Route::get('select-list/{type}', ListRelatedProductForSelectController::class)
        ->middleware('can:view_product');

    Route::get('tag-list', ListTagForSelectController::class)
        ->middleware('can:view_product');

    Route::post('experience/create', CreateExperienceController::class)
     ->middleware('can:view_product');

    Route::post('experience/paginate', PaginateExperienceController::class)
    ->middleware('can:view_product');

    Route::get('experience/detail/{id}', FetchExperienceDetailController::class)
     ->middleware('can:view_product');

    Route::post('experience/update', UpdateExperienceController::class)
     ->middleware('can:view_product');

    //circuit

    Route::post('trek/create', CreateCircuitController::class)
     ->middleware('can:view_product');

    Route::get('trek/detail/{id}', FetchCircuitDetailController::class)
    ->middleware('can:view_product');

    Route::post('trek/paginate', PaginateCircuitController::class)
     ->middleware('can:view_product');

    Route::post('trek/update', UpdateCircuitController::class)
     ->middleware('can:view_product');

    //Tour

    Route::post('tour/create', CreateTourController::class)
        ->middleware('can:view_product');

    Route::get('tour/detail/{id}', FetchTourDetailController::class)
        ->middleware('can:view_product');

    Route::post('tour/paginate', PaginateTourController::class)
        ->middleware('can:view_product');

    Route::post('tour/update', UpdateTourController::class)
        ->middleware('can:view_product');

    //Activities

    Route::post('activities/create', CreateActivitiesController::class)
        ->middleware('can:view_product');

    Route::get('activities/detail/{id}', FetchActivitiesDetailController::class)
        ->middleware('can:view_product');

    Route::post('activities/paginate', PaginateActivitiesController::class)
        ->middleware('can:view_product');

    Route::post('activities/update', UpdateActivitiesController::class)
        ->middleware('can:view_product');

    //package

    Route::post('package/create', CreatePackageController::class)
     ->middleware('can:view_product');

    Route::post('package/update', UpdatePackageController::class)
     ->middleware('can:view_product');

    Route::post('package/paginate', PaginatePackageController::class)
    ->middleware('can:view_product');

    Route::get('package/detail/{id}', GetPackageDetailController::class)
     ->middleware('can:view_product');
});
