<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RusunPemilik extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function getStatusTextAttribute()
    {
        return $this->status ? 'DPT' : 'DPS';
    }

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

    public function rusun_pemilik_dokumens()
    {
        return $this->hasMany(RusunPemilikDokumen::class);
    }

    public function pemiliks()
    {
        return $this->belongsTo(Pemilik::class, 'pemilik_id');
    }

    public function rusun_penghuni()
    {
        return $this->hasOne(RusunPenghuni::class);
    }
}
