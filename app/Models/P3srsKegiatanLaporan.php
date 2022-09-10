<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P3srsKegiatanLaporan extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function p3srs_kegiatan_jadwals()
    {
        return $this->belongsTo(P3srsKegiatanJadwal::class, 'p3srs_kegiatan_jadwal_id');
    }

    public function p3srs_kegiatan_dokumentasis()
    {
        return $this->hasMany(P3srsKegiatanDokumentasi::class);
    }

    public function p3srs_kegiatans()
    {
        return $this->belongsTo(P3srsKegiatan::class, 'p3srs_kegiatan_id');
    }

    public function rusuns()
    {
        return $this->belongsTo(Rusun::class, 'rusun_id');
    }
}
