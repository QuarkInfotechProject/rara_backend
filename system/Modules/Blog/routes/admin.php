<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\App\Http\Controllers\Category\AddCategoryController;
use Modules\Blog\App\Http\Controllers\Category\GetCategoryDetailForUpdateController;
use Modules\Blog\App\Http\Controllers\Category\UpdateCategoryController;
use Modules\Blog\App\Http\Controllers\Category\PaginateCategoryController;
use Modules\Blog\App\Http\Controllers\Category\ListCategoryForSelectController;
use Modules\Blog\App\Http\Controllers\Category\DeleteCategoryController;
use Modules\Blog\App\Http\Controllers\Blog\CreateBlogController;
use Modules\Blog\App\Http\Controllers\Blog\PaginateBlogController;
use Modules\Blog\App\Http\Controllers\Blog\GetUserListForSelectController;
use Modules\Blog\App\Http\Controllers\Blog\GetBlogDetailController;
use Modules\Blog\App\Http\Controllers\Blog\UpdateBlogController;
use Modules\Blog\App\Http\Controllers\Blog\TrashBlogController;
use Modules\Blog\App\Http\Controllers\Blog\RestoreBlogController;


Route::group(['middleware' => ['cors', 'json.response', 'auth:admin'], 'prefix' => 'blog'], function () {
    Route::post('category/add', AddCategoryController::class)
        ->middleware('can:view_blog');

    Route::get('category/detail/{id}', GetCategoryDetailForUpdateController::class)
        ->middleware('can:view_blog');

    Route::post('category/update', UpdateCategoryController::class)
        ->middleware('can:view_blog');

    Route::post('category/paginate', PaginateCategoryController::class)
        ->middleware('can:view_blog');

    Route::post('category/delete', DeleteCategoryController::class)
        ->middleware('can:view_blog');

    Route::get('category/list', ListCategoryForSelectController::class)
        ->middleware('can:view_blog');

    //blogs

    Route::post('create', CreateBlogController::class)
        ->middleware('can:view_blog');

    Route::post('paginate', PaginateBlogController::class)
        ->middleware('can:view_blog');

    Route::get('admin-user-list', GetUserListForSelectController::class)
        ->middleware('can:view_blog');

    Route::get('detail/{id}', GetBlogDetailController::class)
        ->middleware('can:view_blog');

    Route::post('update', UpdateBlogController::class)
        ->middleware('can:view_blog');

    Route::post('trash', TrashBlogController::class)
        ->middleware('can:view_blog');

    Route::post('restore', RestoreBlogController::class)
        ->middleware('can:view_blog');

});
