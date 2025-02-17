<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RusunPenghuni extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function getJenisTextAttribute()
    {    
        switch ($this->jenis) {
            case 'SW':
                $return = 'Sewa';
                break;

            case 'TSW':
                $return = 'Tidak Sewa';
                break;

            case 'KS':
                $return = 'Kosong';
                break;
            
            default:
                $return = '-';
                break;
        }

        return $return;
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

    public function pemiliks()
    {
        return $this->belongsTo(Pemilik::class, 'pemilik_id');
    }

    public function rusun_penghuni_dokumens()
    {
        return $this->hasMany(RusunPenghuniDokumen::class);
    }
}
