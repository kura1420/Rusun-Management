<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiHalaman extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function getHalamanNamaFormatAttribute()
    {
        return ucfirst($this->halaman_nama);
    }

    public function getHalamanAksiFormatAttribute()
    {
        return ucfirst($this->halaman_aksi);
    }
}
