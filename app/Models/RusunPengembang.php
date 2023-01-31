<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RusunPengembang extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function rusun()
    {
        return $this->belongsTo(Rusun::class, 'rusun_id');
    }

    public function pengembang()
    {
        return $this->belongsTo(Pengembang::class, 'pengembang_id');
    }
}
