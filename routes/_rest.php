<?php

use App\Http\Controllers\RestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('rest')
    ->as('rest.')
    ->controller(RestController::class)
    ->group(function () {
        Route::get('provinsi', 'provinsis')->name('provinsis');
        Route::get('kotas', 'kotas')->name('kotas');
        Route::get('kecamatans', 'kecamatans')->name('kecamatans');
        Route::get('desas', 'desas')->name('desas');
        Route::get('rusun-details', 'rusun_details')->name('rusun_details');
        Route::get('pengembangs', 'pengembangs')->name('pengembangs');
        Route::get('pengelolas', 'pengelolas')->name('pengelolas');
    });