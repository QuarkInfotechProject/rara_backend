<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\App\Http\Controllers\Admin\Agent\CreateAgentController;
use Modules\Sales\App\Http\Controllers\Admin\Agent\DisableEnableAgentController;
use Modules\Sales\App\Http\Controllers\Admin\Agent\FetchDetailAgentForUpdateController;
use Modules\Sales\App\Http\Controllers\Admin\Agent\PaginateAgentController;
use Modules\Sales\App\Http\Controllers\Admin\Agent\UpdateAgentController;
use Modules\Sales\App\Http\Controllers\Admin\Booking\AddBookingController;
use Modules\Sales\App\Http\Controllers\Admin\Booking\UpdateBookingController;
use Modules\Sales\App\Http\Controllers\Admin\Booking\GetBookingForUpdateDetailController;
use Modules\Sales\App\Http\Controllers\Admin\Booking\GetBookingDetailController;
use Modules\Sales\App\Http\Controllers\Admin\Booking\PaginateBookingController;
use Modules\Sales\App\Http\Controllers\Admin\Booking\ListAgentForSelectController;
use Modules\Sales\App\Http\Controllers\Admin\Booking\ListProductForSelectController;
use Modules\Sales\App\Http\Controllers\Admin\Booking\ToggleHasRespondedController;
use Modules\Sales\App\Http\Controllers\Admin\Booking\ChangeBookingStatusController;
use Modules\Sales\App\Http\Controllers\Admin\Review\ApproveToggleReviewController;
use Modules\Sales\App\Http\Controllers\Admin\Review\PaginateReviewController;
use Modules\Sales\App\Http\Controllers\Admin\Review\ReplyToReviewController;
use Modules\Sales\App\Http\Controllers\Admin\Review\ViewReviewDetailController;
use Modules\Sales\App\Http\Controllers\Admin\Dashboard\BookingInsightsController;
use Modules\Sales\App\Http\Controllers\Admin\Dashboard\GetBookingByStatusController;
use Modules\Sales\App\Http\Controllers\Admin\Dashboard\TopCountriesController;

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

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'agent'], function () {
    Route::post('create', CreateAgentController::class)
        ->middleware('can:view_agent');

    Route::get('enable-disable/{id}', DisableEnableAgentController::class)
        ->middleware('can:view_agent');

    Route::get('detail/{id}', FetchDetailAgentForUpdateController::class)
        ->middleware('can:view_agent');

    Route::post('paginate', PaginateAgentController::class)
        ->middleware('can:view_agent');

    Route::post('update', UpdateAgentController::class)
        ->middleware('can:view_agent');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'booking'], function () {
    Route::post('new', AddBookingController::class)
        ->middleware('can:view_booking');

    Route::post('update', UpdateBookingController::class)
        ->middleware('can:view_booking');

    Route::get('detail/{id}', GetBookingDetailController::class)
        ->middleware('can:view_booking');

    Route::get('update/detail/{id}', GetBookingForUpdateDetailController::class)
        ->middleware('can:view_booking');

    Route::post('paginate', PaginateBookingController::class)
        ->middleware('can:view_booking');
    ;
    Route::get('list/agent-select', ListAgentForSelectController::class)
        ->middleware('can:view_booking');

    Route::get('list/product-select', ListProductForSelectController::class)
        ->middleware('can:view_booking');

    Route::get('toggle-respond/{id}', ToggleHasRespondedController::class)
        ->middleware('can:view_booking');

    Route::post('change-status', ChangeBookingStatusController::class)
        ->middleware('can:view_booking');
});

Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'review'], function () {
    Route::post('paginate', PaginateReviewController::class)
    ->middleware('can:view_review');

    Route::get('approve-toggle/{id}', ApproveToggleReviewController::class)
    ->middleware('can:view_review');

    Route::get('detail/{id}', ViewReviewDetailController::class)
    ->middleware('can:view_review');

    Route::post('reply', ReplyToReviewController::class)
    ->middleware('can:view_review');
});


Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'dashboard'], function () {

    Route::post('booking-insights', BookingInsightsController::class);

    Route::post('booking-status', GetBookingByStatusController::class);

    Route::post('top-country', TopCountriesController::class);
});
