<?php

use App\Http\Controllers\ApiManagementController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformasiHalamanController;
use App\Http\Controllers\KomplainController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\PengelolaDokumenController;
use App\Http\Controllers\PengelolaKontakController;
use App\Http\Controllers\PengembangController;
use App\Http\Controllers\PengembangDokumenController;
use App\Http\Controllers\PengembangKontakController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PollingKanidatController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramDokumenController;
use App\Http\Controllers\ProgramJabatanController;
use App\Http\Controllers\ProgramKanidatController;
use App\Http\Controllers\ProgramKanidatDokumenController;
use App\Http\Controllers\ProgramKegiatanController;
use App\Http\Controllers\ProgramLaporanController;
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
use App\Http\Controllers\UserPemdaController;
use App\Http\Controllers\UserPemilikController;
use App\Http\Controllers\UserPengelolaController;
use App\Http\Controllers\UserPengembangController;
use App\Http\Controllers\UserPenghuniController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserRusunController;
use Illuminate\Http\Request;
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

Route::prefix('blog')
    ->as('blog.')
    ->controller(BlogController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/program/{slug}', 'programShow')->name('program-show');
    });

Route::get('/verify/{id}/{token}', function (Request $request) {
    if (auth()->check()) {
        return abort(404);
    }

    $user = \App\Models\User::where([
        ['id', $request->id],
        ['remember_token', $request->token]
    ])->firstOrFail();

    $user->update([
        'active' => 1,
        'remember_token' => NULL,
    ]);

    return redirect('/login')
        ->with([
            'success' => 'User telah diverifikasi, silahkan login',
        ]);
})->name('verify_user');

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {

    require_once '_public.php';
    require_once '_rest.php';

    // beranda
    Route::controller(HomeController::class)
        ->as('beranda.')
        ->group(function () {
            Route::get('/penghuni', 'penghuni')->name('penghuni');
        });
    // end beranda

    // profile user
    Route::prefix('profile')
        ->as('profile.')
        ->controller(UserProfileController::class)
        ->group(function () {
            Route::get('/{username}', 'edit')->name('edit');
            Route::put('/{username}', 'update')->name('update');
        });
    // end profile user

    // role & permission
    Route::middleware(['role:Root'])->group(function () {
        Route::resources([
            'role' => RoleController::class,
            'permission' => PermissionController::class,
        ]);
    });
    // end role & permission

    // setting
    Route::middleware(['role:Root|Admin'])->group(function () {
        Route::resources([
            'user' => UserController::class,
                'user-pemda' => UserPemdaController::class,
                'user-rusun' => UserRusunController::class,
                'user-pengembang' => UserPengembangController::class,
                'user-pengelola' => UserPengelolaController::class,
                'user-pemilik' => UserPemilikController::class,
                'user-penghuni' => UserPenghuniController::class,
    
            'api-manage' => ApiManagementController::class,

            'faq' => FaqController::class,
            'dokumen' => DokumenController::class,
            'informasi-halaman' => InformasiHalamanController::class,
        ]);

        Route::prefix('api-manage')
            ->as('api-manage.')
            ->controller(ApiManagementController::class)
            ->group(function () {
                Route::get('/list/data', 'listData')->name('list-data');
                Route::get('/{id}/sync-manual', 'syncManual')->name('sync-manual');
            });

        Route::prefix('informasi-halaman')->group(function () {
            Route::controller(InformasiHalamanController::class)->group(function () {
                Route::get('/{id}/copy', 'copy')->name('informasi-halaman.copy');
                Route::get('/{id}/view-file/{file}', 'view_file')->name('informasi-halaman.view_file');
            });
        });
    });
    // end setting

    // pengembang
    Route::middleware(['role:Root|Admin|Pengembang'])->group(function () {
        Route::resources([
            'pengembang' => PengembangController::class,
            'pengembang-kontak' => PengembangKontakController::class,
        ]);

        Route::resource('pengembang-dokumen', PengembangDokumenController::class)->except('show');
    });

    Route::prefix('pengembang-dokumen')
        ->as('pengembang-dokumen.')
        ->controller(PengembangDokumenController::class)
        ->middleware(['role_or_permission:Root|Admin|Pengembang|Verif Dokumen'])
        ->group(function () {
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/view-file/{filename}', 'view_file')->name('view_file');
            Route::put('/{id}/verif', 'verifUpdate')->name('verif');
        });
    // end pengembang

    // pengelola
    Route::middleware(['role:Root|Admin|Pengelola'])->group(function () {
        Route::resources([
            'pengelola' => PengelolaController::class,
            'pengelola-kontak' => PengelolaKontakController::class,
        ]);

        Route::resource('pengelola-dokumen', PengelolaDokumenController::class)->except('show');
    });

    Route::prefix('pengelola-dokumen')
            ->as('pengelola-dokumen.')
            ->controller(PengelolaDokumenController::class)
            ->middleware(['role_or_permission:Root|Admin|Pengelola|Verif Dokumen'])
            ->group(function () {
                Route::get('/{id}', 'show')->name('show');
                Route::get('/{id}/view-file/{filename}', 'view_file')->name('view_file');

                Route::put('/{id}/verif', 'verifUpdate')->name('verif');
            });

    Route::prefix('pengelola')->group(function () {
        Route::controller(PengelolaController::class)->group(function () {
            Route::get('/rest/search', 'apiList')->name('pengelola.apiList');
        });
    });
    // end pengelola

    // rusun
    Route::middleware(['role:Root|Admin|Pemda|Rusun'])->group(function () {
        Route::resources([
            'rusun' => RusunController::class,
            'rusun-detail' => RusunDetailController::class,
            'rusun-unit-detail' => RusunUnitDetailController::class,
            'rusun-fasilitas' => RusunFasilitasController::class,
            'rusun-pembayaran-ipl' => RusunPembayaranIplController::class,
        ]);

        Route::prefix('rusun')->group(function () {
            Route::controller(RusunController::class)->group(function () {
                Route::get('/{id}/view-file/{filename}', 'view_file')->name('rusun.view_file');
                Route::get('/{id}/pengembang-dokumen', 'pengembangDokumen')->name('rusun.pengembangDokumen');
                Route::get('/{id}/pengelola-dokumen', 'pengelolaDokumen')->name('rusun.pengelolaDokumen');
    
                Route::post('/{id}', 'updateAsStore')->name('rusun.updateAsStore');
    
                Route::delete('/rusun-pengelola/{id}', 'pengelolaDestroy')->name('rusun.pengelolaDestroy');
                Route::delete('/rusun-pengembang/{id}', 'pengembangDestroy')->name('rusun.pengembangDestroy');
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
    });
    // end rusun

    // pemilik
    Route::prefix('pemilik')
        ->as('pemilik.')
        ->controller(PemilikController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['role:Root|Admin|Pemda|Rusun|Pemilik']);
            Route::get('/{id}', 'show')->name('show')->middleware(['role:Root|Admin|Pemda|Rusun|Pemilik']);
            Route::get('/{id}/edit', 'edit')->name('edit')->middleware(['role:Root|Admin|Pemilik']);
            Route::get('/{id}/view-file/{file}', 'view_file')->name('view_file');

            Route::put('/{id}', 'update')->name('update')->middleware(['role:Root|Admin|Pemilik']);
        });
    
    Route::prefix('rusun-pemilik')
        ->as('rusun-pemilik.')
        ->controller(RusunPemilikController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['role:Root|Admin|Pemda|Rusun|Pemilik']);
            Route::get('/{id}', 'show')->name('show')->middleware(['role:Root|Admin|Pemda|Rusun|Pemilik']);
            Route::get('/{id}/edit', 'edit')->name('edit')->middleware(['role:Root|Admin']);
            Route::get('/{id}/view-file/{file}', 'view_file')->name('view_file');

            Route::put('/{id}', 'update')->name('update')->middleware(['role:Root|Admin']);
        });

    Route::prefix('rusun-pemilik-dokumen')
        ->as('rusun-pemilik-dokumen.')
        ->controller(RusunPemilikDokumenController::class)
        ->group(function () {
            Route::get('/create', 'create')->name('create')->middleware(['role:Root|Admin|Pemilik']);
            Route::get('/{id}', 'show')->name('show')->middleware(['role:Root|Admin|Pemda|Rusun|Pemilik']);
            Route::get('/{id}/edit', 'edit')->name('edit')->middleware(['role:Root|Admin|Pemilik']);
            
            Route::post('/', 'store')->name('store')->middleware(['role:Root|Admin|Pemilik']);
            
            Route::put('/{id}', 'update')->name('update')->middleware(['role:Root|Admin|Pemilik']);

            Route::delete('/{id}', 'destroy')->name('destroy')->middleware(['role:Root|Admin|Pemilik']);
        });
    // end pemilik

    // penghuni
    Route::prefix('rusun-penghuni')
        ->as('rusun-penghuni.')
        ->controller(RusunPenghuniController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['role:Root|Admin|Pemda|Rusun|Penghuni']);
            Route::get('/{id}', 'show')->name('show')->middleware(['role:Root|Admin|Pemda|Rusun|Penghuni']);
            Route::get('/{id}/edit', 'edit')->name('edit')->middleware(['role:Root|Admin|Penghuni']);
            Route::get('/{id}/view-file/{file}', 'view_file')->name('view_file');
            Route::get('/list/data', 'listData')->name('list-data');

            Route::put('/{id}', 'update')->name('update')->middleware(['role:Root|Admin|Penghuni']);
        });

    Route::prefix('rusun-penghuni-dokumen')
        ->as('rusun-penghuni-dokumen.')
        ->controller(RusunPenghuniDokumenController::class)
        ->group(function () {
            Route::get('/create', 'create')->name('create')->middleware(['role:Root|Admin|Penghuni']);
            Route::get('/{id}', 'show')->name('show')->middleware(['role:Root|Admin|Pemda|Rusun|Penghuni']);
            Route::get('/{id}/edit', 'edit')->name('edit')->middleware(['role:Root|Admin|Penghuni']);
            
            Route::post('/', 'store')->name('store')->middleware(['role:Root|Admin|Penghuni']);
            
            Route::put('/{id}', 'update')->name('update')->middleware(['role:Root|Admin|Penghuni']);

            Route::delete('/{id}', 'destroy')->name('destroy')->middleware(['role:Root|Admin|Penghuni']);
        });
    // end penghuni

    // program & kegiatan
    Route::middleware(['role_or_permission:Root|Admin|Pemda|Rusun|Verif Dokumen'])->group(function () {
        Route::resources([
            'program' => ProgramController::class,
            'program-jabatan' => ProgramJabatanController::class,
            'program-dokumen' => ProgramDokumenController::class,
            'program-kegiatan' => ProgramKegiatanController::class,
            'program-laporan' => ProgramLaporanController::class,
        ]);

        Route::prefix('program-laporan')
            ->as('program-laporan.')
            ->controller(ProgramLaporanController::class)
            ->group(function () {
                Route::get('/{id}/view-file/{filename}', 'dokumentasiViewFile')->name('view-file');

                Route::delete('/{id}/dokumentasi-delete', 'dokumentasiDestroy')->name('dokumentasi-delete');
            });
    });

    Route::middleware(['role_or_permission:Root|Admin|Pemda|Rusun|Pemilik|Penghuni|Verif Dokumen'])->group(function () {
        Route::resources([
            'program-kanidat' => ProgramKanidatController::class,
            'program-kanidat-dokumen' => ProgramKanidatDokumenController::class,
            'polling-kanidat' => PollingKanidatController::class,
        ]);

        Route::prefix('program-kanidat')
            ->as('program-kanidat.')
            ->controller(ProgramKanidatController::class)
            ->group(function () {
                Route::get('/list/data', 'listData')->name('list-data');
                Route::get('/{programId}/{grupId}/detail', 'showDetail')->name('show-detail');
                Route::get('/register/{id}', 'register')->name('register');

                Route::put('/status/{id}', 'updateStatus')->name('update-status');
            });

        Route::prefix('program-kanidat-dokumen')
            ->as('program-kanidat-dokumen.')
            ->controller(ProgramKanidatDokumenController::class)
            ->group(function () {
                Route::get('/view-file/{id}/{file}', 'view_file')->name('view_file');
            });
    });
    // end program & kegiatan

    // komplain
    Route::resource('komplain', KomplainController::class);

    Route::prefix('komplain')->group(function () {
        Route::controller(KomplainController::class)->group(function () {
            Route::get('/rest/search', 'apiList')->name('komplain.apiList');
            Route::get('/page/q', 'pages')->name('komplain.pages');
            Route::get('/{id}/view-file/{file}', 'view_file')->name('komplain.view_file');
            Route::get('/{id}/tanggapi', 'tanggapi')->name('komplain.tanggapi');
            Route::get('/{fk}/{id}/tanggapi', 'tanggapiShow')->name('komplain.tanggapiShow');
            Route::get('/{id}/ditanggapi-kembali', 'tanggapiKembali')->name('komplain.tanggapiKembali');

            Route::post('/{id}/tanggapi', 'tanggapiStore')->name('komplain.tanggapiStore');
            Route::post('/{id}/ditanggapi-kembali', 'tanggapiKembaliStore')->name('komplain.tanggapiKembaliStore');
        });
    });
    // end komplain
});