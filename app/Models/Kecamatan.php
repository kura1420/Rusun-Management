<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'districts';

    protected $guarded = [];

    public $timestamps = false;

    public function kotas()
    {
        return $this->belongsTo(Kota::class, 'regency_id');
    }

    public function desas()
    {
        return $this->hasMany(Desa::class, 'district_id');
    }
}
