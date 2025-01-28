<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NYTBestSellersController;


Route::prefix('api/v1')->group(function () {
    Route::get('/nyt/best-sellers', [NYTBestSellersController::class, 'index'])->name('nyt.best-sellers');
});