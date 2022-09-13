<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P3srsKegiatanJadwal extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function p3srs_kegiatans()
    {
        return $this->belongsTo(P3srsKegiatan::class, 'p3srs_kegiatan_id');
    }

    public function rusuns()
    {
        return $this->belongsTo(Rusun::class, 'rusun_id');
    }

    public function p3srs_kegiatan_kanidats()
    {
        return $this->hasMany(P3srsKegiatanKanidat::class)
            ->orderBy('grup_nama', 'asc');
    }

    public function p3srs_kegiatan_anggotas()
    {
        return $this->hasMany(P3srsKegiatanAnggota::class);
    }

    public function p3srs_kegiatan_laporans()
    {
        return $this->hasMany(P3srsKegiatanLaporan::class, 'p3srs_kegiatan_jadwal_id')
            ->orderBy('tanggal', 'asc');
    }
}
