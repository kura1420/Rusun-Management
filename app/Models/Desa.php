<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'villages';

    protected $guarded = [];

    public $timestamps = false;

    public function kecamatans()
    {
        return $this->belongsTo(Kecamatan::class, 'district_id');
    }
}
