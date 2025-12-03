<?php

use Illuminate\Support\Facades\Route;
use Modules\Newsletter\App\Http\Controllers\NewsletterController;

Route::middleware(['cors', 'json.response'])->post('/subscribers', [NewsletterController::class, 'store']);

