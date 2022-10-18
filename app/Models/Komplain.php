<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function komplain_tanggapans()
    {
        return $this->hasMany(KomplainTanggapan::class)
            ->orderBy('created_at');
    }

    public function komplain_files()
    {
        return $this->hasMany(KomplainFile::class);
    }

    public function komplain_user_bukas()
    {
        return $this->hasMany(KomplainUserBuka::class);
    }

    public function pengelola()
    {
        return $this->belongsTo(Pengelola::class);
    }

    public function rusun()
    {
        return $this->belongsTo(Rusun::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'komplain_user_id');
    }

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
}
