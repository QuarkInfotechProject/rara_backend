<?php

use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\Admin\PaginateUserController;
use Modules\User\App\Http\Controllers\Admin\ToggleBlockUnblockUserController;
use Modules\User\App\Http\Controllers\Admin\ViewUserController;
use Modules\User\App\Http\Controllers\Profile\UserProfileShowController;
use Modules\User\App\Http\Controllers\Profile\UserProfileUpdateController;
use Modules\User\App\Http\Controllers\UserChangePasswordController;
use Modules\User\App\Http\Controllers\UserForgotPasswordController;
use Modules\User\App\Http\Controllers\UserLoginController;
use Modules\User\App\Http\Controllers\UserLogoutController;
use Modules\User\App\Http\Controllers\UserRegisterController;
use Modules\User\App\Http\Controllers\UserResetPasswordController;
use Modules\User\App\Http\Controllers\UserSendRegisterMailController;
use Modules\User\App\Http\Controllers\UserSocialLoginCallbackController;
use Modules\User\App\Http\Controllers\UserSocialLoginRedirectController;
use Modules\User\App\Http\Controllers\UserChangeProfilePictureController;
use Modules\User\App\Http\Controllers\NextErrorLogs\AddNextErrorLogController;


Route::group(['middleware' => 'auth:admin'], function () {
    Route::post('/user/paginate', PaginateUserController::class);

    Route::get('/user/toggle-block-unblock/{id}', ToggleBlockUnblockUserController::class);
    Route::get('/user/view/{id}', ViewUserController::class);
});
