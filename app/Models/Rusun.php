<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rusun extends Model
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

    public function rusun_details()
    {
        return $this->hasMany(RusunDetail::class);
    }

    public function rusun_unit_details()
    {
        return $this->hasMany(RusunUnitDetail::class);
    }

    public function rusun_fasilitas()
    {
        return $this->hasMany(RusunFasilitas::class);
    }
}
