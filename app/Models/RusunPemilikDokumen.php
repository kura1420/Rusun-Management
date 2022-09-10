<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RusunPemilikDokumen extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function rusuns()
    {
        return $this->belongsTo(Rusun::class, 'rusun_id');
    }

    public function rusun_details()
    {
        return $this->belongsTo(RusunDetail::class, 'rusun_detail_id');
    }

    public function rusun_unit_details()
    {
        return $this->belongsTo(RusunUnitDetail::class, 'rusun_unit_detail_id');
    }

    public function rusun_pemiliks()
    {
        return $this->belongsTo(RusunPemilik::class, 'rusun_pemilik_id');
    }

    public function dokumens()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }
}
