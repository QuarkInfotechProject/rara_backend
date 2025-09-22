<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\App\Http\Controllers\User\Booking\AddBookingController;
use Modules\Sales\App\Http\Controllers\User\Review\AddReviewsAndRatingController;
use Modules\Sales\App\Http\Controllers\User\Review\GetBookingHistoryWithReviewsAndRatingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['cors', 'json.response', 'auth:user'], 'prefix' => 'booking'], function () {

    Route::post('new', AddBookingController::class);

});

//Route::group(['middleware' => ['cors', 'json.response', 'auth:user'], 'prefix' => 'profile'], function () {
//
//    Route::post('add-review', AddReviewsAndRatingController::class);
//    Route::get('get-booking-history', GetBookingHistoryWithReviewsAndRatingController::class);
//
//});

Route::group(['middleware' => ['cors', 'json.response'], 'prefix' => 'profile'], function () {

//    Route::post('add-review', AddReviewsAndRatingController::class);
//    Route::get('get-booking-history', GetBookingHistoryWithReviewsAndRatingController::class);
});
Route::post('profile/add-review', AddReviewsAndRatingController::class);
Route::get('profile/get-booking-history', GetBookingHistoryWithReviewsAndRatingController::class);
