<?php

use Illuminate\Support\Facades\Route;
use Modules\Newsletter\App\Http\Controllers\NewsletterController;

Route::middleware(['cors', 'json.response', 'auth:admin', 'can:view_newsletter'])->group(function () {
    Route::get('/subscribers', [NewsletterController::class, 'index']);
    Route::delete('/subscribers/{id}', [NewsletterController::class, 'destroy']);
});