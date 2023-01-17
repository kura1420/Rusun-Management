<?php

use App\Http\Controllers\FaqController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('faq')->group(function () {
    Route::controller(FaqController::class)->group(function () {
        Route::get('helps/users', 'helps')->name('faq.helps');
    });
});