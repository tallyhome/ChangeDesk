<?php

use App\Http\Controllers\Api\SslAskController;
use Illuminate\Support\Facades\Route;

Route::get('/ssl/ask', SslAskController::class)->name('api.ssl.ask');
