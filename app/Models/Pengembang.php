<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembang extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function provinces()
    {
        return $this->belongsTo(Provinsi::class, 'province_id');
    }

    public function kotas()
    {
        return $this->belongsTo(Kota::class, 'regencie_id');
    }

    public function kecamatans()
    {
        return $this->belongsTo(Kecamatan::class, 'district_id');
    }

    public function desas()
    {
        return $this->belongsTo(Desa::class, 'village_id');
    }

    public function pengembang_kontaks()
    {
        return $this->hasMany(PengembangKontak::class);
    }

    public function pengembang_dokumens()
    {
        return $this->hasMany(PengembangDokumen::class);
    }
}
