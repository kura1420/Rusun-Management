<?php

namespace App\Helpers;

use App\Models\InformasiHalaman;
use Illuminate\Support\Facades\Route;

class PageAction {

    public static function getInformationPage()
    {
        $route = Route::current();
        $routeAction = $route->action['as'];
        $routeActionArray = explode('.', $routeAction);

        $informasiHalaman = InformasiHalaman::where([
            ['halaman_nama', $routeActionArray[0] ?? NULL],
            ['halaman_aksi', 'full'],
        ])->first();

        if (!$informasiHalaman) {
            $informasiHalaman = InformasiHalaman::where([
                ['halaman_nama', $routeActionArray[0] ?? NULL],
                ['halaman_aksi', $routeActionArray[1] ?? NULL],
            ])->first();
        }

        return $informasiHalaman;
    }

}