<?php

use App\Http\Controllers\NYTBestSellersController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/nyt/best-sellers', NYTBestSellersController::class)->name('nyt.best-sellers');
});
