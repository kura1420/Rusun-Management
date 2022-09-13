<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P3srsKegiatanKanidat extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function p3srs_jabatans()
    {
        return $this->belongsTo(P3srsJabatan::class, 'p3srs_jabatan_id');
    }

    public function getPemilikPenghuniProfileAttribute()
    {
        if ($this->apakah_pemilik) {
            return \App\Models\RusunPemilik::join('pemiliks', 'rusun_pemiliks.pemilik_id', '=', 'pemiliks.id')
                ->where('rusun_pemiliks.id', $this->pemilik_penghuni_id)
                ->first();
        } else {
            return \App\Models\RusunPenghuni::where('id', $this->pemilik_penghuni_id)->first();
        }        
    }
}
