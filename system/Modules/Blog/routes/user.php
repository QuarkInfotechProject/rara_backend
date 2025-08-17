<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\App\Http\Controllers\User\ListMediaCoverageController;
use Modules\Blog\App\Http\Controllers\User\ListMediaCoverageForHomepageController;
use Modules\Blog\App\Http\Controllers\User\ListBlogsForHomepageController;
use Modules\Blog\App\Http\Controllers\User\ListBlogController;
use Modules\Blog\App\Http\Controllers\User\ListCategoriesForSelectController;
use Modules\Blog\App\Http\Controllers\User\GetBlogDetailController;
use Modules\Blog\App\Http\Controllers\User\ListAllBlogSlugController;


Route::group(['middleware' => ['cors', 'json.response'], 'prefix' => 'homepage'], function () {
    Route::get('media-coverage', ListMediaCoverageForHomepageController::class);
    Route::get('media-coverage/list', ListMediaCoverageController::class);
    Route::get('blog', ListBlogsForHomepageController::class);
});

Route::group(['middleware' => ['cors', 'json.response'], 'prefix' => 'blog'], function () {
    Route::post('paginate', ListBlogController::class);
    Route::get('list-category', ListCategoriesForSelectController::class);
    Route::get('detail/{slug}', GetBlogDetailController::class);
    Route::get('slug-list', ListAllBlogSlugController::class);
});
