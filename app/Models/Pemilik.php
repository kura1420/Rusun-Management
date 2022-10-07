<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function rusun_pemiliks()
    {
        return $this->hasMany(RusunPemilik::class);
    }

    public function rusun_pemilik_dokumens()
    {
        return $this->hasMany(RusunPemilikDokumen::class);
    }

    public function rusun_penghunis()
    {
        return $this->hasMany(RusunPenghuni::class);
    }

    public function rusun_pembayaran_ipls()
    {
        return $this->hasMany(RusunPembayaranIpl::class);
    }
}
