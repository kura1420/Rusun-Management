<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RusunPemilik extends Model
{
    use HasFactory;

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

    public function rusun_penghunis()
    {
        return $this->hasMany(RusunPenghuni::class, 'rusun_penghuni_id');
    }
}
