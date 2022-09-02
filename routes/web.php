<?php

use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\PengelolaDokumenController;
use App\Http\Controllers\PengelolaKontakController;
use App\Http\Controllers\PengembangController;
use App\Http\Controllers\PengembangDokumenController;
use App\Http\Controllers\PengembangKontakController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RusunController;
use App\Http\Controllers\RusunDetailController;
use App\Http\Controllers\RusunFasilitasController;
use App\Http\Controllers\RusunUnitDetailController;
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

    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);
    
    Route::resource('pengelola', PengelolaController::class);
    Route::resource('pengelola-kontak', PengelolaKontakController::class);
    Route::resource('pengelola-dokumen', PengelolaDokumenController::class);

    Route::resource('pengembang', PengembangController::class);
    Route::resource('pengembang-kontak', PengembangKontakController::class);
    Route::resource('pengembang-dokumen', PengembangDokumenController::class);

    Route::resource('rusun', RusunController::class);
    Route::resource('rusun-detail', RusunDetailController::class);
    Route::resource('rusun-unit-detail', RusunUnitDetailController::class);
    Route::resource('rusun-fasilitas', RusunFasilitasController::class);

    
    Route::prefix('pengelola-dokumen')->group(function () {
        Route::controller(PengelolaDokumenController::class)->group(function () {
            Route::get('{id}/view-file/{filename}', 'view_file')->name('pengelola-dokumen.view_file');
        });
    });
    
    Route::prefix('pengembang-dokumen')->group(function () {
        Route::controller(PengembangDokumenController::class)->group(function () {
            Route::get('{id}/view-file/{filename}', 'view_file')->name('pengembang-dokumen.view_file');
        });
    });

    Route::prefix('rusun')->group(function () {
        Route::controller(RusunController::class)->group(function () {
            Route::post('/{id}', 'updateAsStore')->name('rusun.updateAsStore');
            Route::get('{id}/view-file/{filename}', 'view_file')->name('rusun.view_file');
        });
    });

    Route::prefix('rusun-unit-detail')->group(function () {
        Route::controller(RusunUnitDetailController::class)->group(function () {
            Route::post('/{id}', 'updateAsStore')->name('rusun-unit-detail.updateAsStore');
        });
    });

    Route::prefix('rusun-fasilitas')->group(function () {
        Route::controller(RusunFasilitasController::class)->group(function () {
            Route::get('/{id}/view-file/{foto}', 'view_file')->name('rusun-fasilitas.view_file');
            Route::post('/{id}', 'updateAsStore')->name('rusun-fasilitas.updateAsStore');
        });
    });

    Route::group(['prefix' => 'rest', 'as' => 'rest.'],
        function () {
            Route::controller(RestController::class)->group(function () {
                Route::get('/provinsi', 'provinsis')->name('provinsis');
                Route::get('/kotas', 'kotas')->name('kotas');
                Route::get('/kecamatans', 'kecamatans')->name('kecamatans');
                Route::get('/desas', 'desas')->name('desas');
                Route::get('/rusun-details', 'rusun_details')->name('rusun_details');
            });
        }
    );
});