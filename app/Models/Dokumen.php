<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function getKepadaLabelAttribute()
    {
        return ucfirst($this->kepada);
    }

    public function pengelola_dokumens()
    {
        return $this->hasMany(PengelolaDokumen::class);
    }

    public function pengembang_dokumens()
    {
        return $this->hasMany(PengembangDokumen::class);
    }

    public function rusun_pemilik_dokumens()
    {
        return $this->hasMany(RusunPemilikDokumen::class);
    }

    public function rusun_penghuni_dokumens()
    {
        return $this->hasMany(RusunPenghuniDokumen::class);
    }
}
