<?php

use App\Http\Controllers\Api\PemilikController;
use App\Http\Controllers\Api\RusunController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(
    [
        'middleware' => 'auth.basic'
    ],
    function () {
        Route::prefix('rusun')
            ->as('rusun.')
            ->controller(RusunController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });

        Route::prefix('pemilik')
            ->as('pemilik.')
            ->controller(PemilikController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });
    }
);