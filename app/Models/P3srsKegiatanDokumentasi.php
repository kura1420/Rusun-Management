<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P3srsKegiatanDokumentasi extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function p3srs_kegiatan_laporans()
    {
        return $this->belongsTo(P3srsKegiatanLaporan::class, 'p3srs_kegiatan_laporan_id');
    }

    public function p3srs_kegiatan_jadwals()
    {
        return $this->belongsTo(P3srsKegiatanJadwal::class, 'p3srs_kegiatan_jadwal_id');
    }
}
