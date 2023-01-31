<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'regencies';

    protected $guarded = [];

    public $timestamps = false;

    public function provinces()
    {
        return $this->belongsTo(Kota::class, 'province_id');
    }

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class, 'district_id');
    }
}
