<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RusunPenghuni extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function rusuns()
    {
        return $this->belongsTo(Rusun::class, 'rusun_id');
    }
}
