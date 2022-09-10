<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RusunPembayaranIpl extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function getPemilikPenghuniNamaAttribute()
    {
        if ($this->pemilik_bayar) {
            return Pemilik::where('id', $this->pemilik_penghuni_id)->first();
        } else {
            return RusunPenghuni::where('id', $this->pemilik_penghuni_id)->first();
        }
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
}
