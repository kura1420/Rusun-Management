<?php

namespace App\Models;

use App\Helpers\Formatter;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RusunOutstandingPenghuni extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function getTotalFormatAttribute()
    {
        return Formatter::rupiah($this->total);
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

    public function rusun_outstanding_details()
    {
        return $this->hasMany(RusunOutstandingDetail::class);
    }
}
