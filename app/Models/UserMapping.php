<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMapping extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provinces()
    {
        return $this->belongsTo(Provinsi::class, 'province_id');
    }

    public function kotas()
    {
        return $this->belongsTo(Kota::class, 'regencie_id');
    }
}
