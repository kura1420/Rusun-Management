<?php

use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\PengembangController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::group([ 'prefix' => 'user', 'as' => 'user.', ], 
        function () {
            Route::controller(UserController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/{id}/edit', 'edit')->name('edit');
    
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
            });
        }
    );
    
    Route::group([ 'prefix' => 'role', 'as' => 'role.', ],
        function () {
            Route::controller(RoleController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/{id}/edit', 'edit')->name('edit');
    
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
            });
        }
    );
    
    Route::group([ 'prefix' => 'permission', 'as' => 'permission.' ], 
        function () {
            Route::controller(PermissionController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/{id}/edit', 'edit')->name('edit');
        
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
            });
        }
    );
    
    Route::group(['prefix' => 'pengelola', 'as' => 'pengelola.'],
        function () {
            Route::controller(PengelolaController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/{id}/edit', 'edit')->name('edit');
        
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        }
    );
    
    Route::group(['prefix' => 'pengembang', 'as' => 'pengembang.'], 
        function () {
            Route::controller(PengembangController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/{id}/edit', 'edit')->name('edit');
        
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        }
    );
});