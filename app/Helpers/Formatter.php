<?php

namespace App\Helpers;

use App\Helpers\Terbilang;

class Formatter {

    public static function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');

	    return $hasil_rupiah;
    }
}