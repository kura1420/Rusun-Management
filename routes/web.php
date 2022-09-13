<?php

use App\Http\Controllers\DokumenController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\InformasiHalamanController;
use App\Http\Controllers\P3srsJabatanController;
use App\Http\Controllers\P3srsKegiatanAnggotaController;
use App\Http\Controllers\P3srsKegiatanController;
use App\Http\Controllers\P3srsKegiatanJadwalController;
use App\Http\Controllers\P3srsKegiatanKanidatController;
use App\Http\Controllers\P3srsKegiatanLaporanController;
use App\Http\Controllers\PemilikController;
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
use App\Http\Controllers\RusunPembayaranIplController;
use App\Http\Controllers\RusunPemilikController;
use App\Http\Controllers\RusunPemilikDokumenController;
use App\Http\Controllers\RusunPenghuniController;
use App\Http\Controllers\RusunPenghuniDokumenController;
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

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // setting
    Route::resources([
        'user' => UserController::class,
        'role' => RoleController::class,
        'permission' => PermissionController::class,
    ]);

    // master
    Route::resources([
        'faq' => FaqController::class,
        'dokumen' => DokumenController::class,
        'informasi-halaman' => InformasiHalamanController::class,
    ]);

    // pengembang
    Route::resources([
        'pengembang' => PengembangController::class,
        'pengembang-kontak' => PengembangKontakController::class,
        'pengembang-dokumen' => PengembangDokumenController::class,
    ]);

    // pengelola
    Route::resources([
        'pengelola' => PengelolaController::class,
        'pengelola-kontak' => PengelolaKontakController::class,
        'pengelola-dokumen' => PengelolaDokumenController::class,
    ]);

    // rusun
    Route::resources([
        'rusun' => RusunController::class,
        'rusun-detail' => RusunDetailController::class,
        'rusun-unit-detail' => RusunUnitDetailController::class,
        'rusun-fasilitas' => RusunFasilitasController::class,
        'rusun-pembayaran-ipl' => RusunPembayaranIplController::class,
        
        'pemilik' => PemilikController::class,
        'rusun-pemilik' => RusunPemilikController::class,
        'rusun-pemilik-dokumen' => RusunPemilikDokumenController::class,

        'rusun-penghuni' => RusunPenghuniController::class,
        'rusun-penghuni-dokumen' => RusunPenghuniDokumenController::class,
    ]);

    // p3srs
    Route::resources([
        'p3srs-jabatan' => P3srsJabatanController::class,
        'p3srs-kegiatan' => P3srsKegiatanController::class,
        'p3srs-jadwal' => P3srsKegiatanJadwalController::class,
        'p3srs-kegiatan-kanidat' => P3srsKegiatanKanidatController::class,
        'p3srs-kegiatan-anggota' => P3srsKegiatanAnggotaController::class,
        'p3srs-kegiatan-laporan' => P3srsKegiatanLaporanController::class,
    ]);


    // other CRUD
    Route::prefix('faq')->group(function () {
        Route::controller(FaqController::class)->group(function () {
            Route::get('helps/users', 'helps')->name('faq.helps');
        });
    });
    
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
            Route::get('{id}/view-file/{filename}', 'view_file')->name('rusun.view_file');
            Route::get('{id}/pengembang-dokumen', 'pengembangDokumen')->name('rusun.pengembangDokumen');
            Route::get('{id}/pengelola-dokumen', 'pengelolaDokumen')->name('rusun.pengelolaDokumen');

            Route::post('{id}', 'updateAsStore')->name('rusun.updateAsStore');

            Route::delete('rusun-pengelola/{id}', 'pengelolaDestroy')->name('rusun.pengelolaDestroy');
            Route::delete('rusun-pengembang/{id}', 'pengembangDestroy')->name('rusun.pengembangDestroy');
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

    Route::prefix('pemilik')->group(function () {
        Route::controller(PemilikController::class)->group(function () {
            Route::get('/{id}/view-file/{file}', 'view_file')->name('pemilik.view_file');
        });
    });

    Route::prefix('rusun-penghuni')->group(function () {
        Route::controller(RusunPenghuniController::class)->group(function () {
            Route::get('/{id}/view-file/{file}', 'view_file')->name('rusun-penghuni.view_file');
        });
    });

    Route::prefix('p3srs-kegiatan-laporan')->group(function () {
        Route::controller(P3srsKegiatanLaporanController::class)->group(function () {
            Route::get('view-file/{id}/{filename}', 'dokumentasiViewFile')->name('p3srs-kegiatan-laporan.dokumentasiViewFile');
        });
    });

    Route::prefix('p3srs-kegiatan-kanidat')->group(function () {
        Route::controller(P3srsKegiatanKanidatController::class)->group(function () {
            Route::delete('grup-delete/{groupId}', 'destroyGroup')->name('p3srs-kegiatan-kanidat.destroyGroup');
        });
    });

    Route::group(['prefix' => 'rest', 'as' => 'rest.'],
        function () {
            Route::controller(RestController::class)->group(function () {
                Route::get('provinsi', 'provinsis')->name('provinsis');
                Route::get('kotas', 'kotas')->name('kotas');
                Route::get('kecamatans', 'kecamatans')->name('kecamatans');
                Route::get('desas', 'desas')->name('desas');
                Route::get('rusun-details', 'rusun_details')->name('rusun_details');
                Route::get('pengembangs', 'pengembangs')->name('pengembangs');
                Route::get('pengelolas', 'pengelolas')->name('pengelolas');
            });
        }
    );
});