<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KomplainFile extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function komplain()
    {
        return $this->belongsTo(Komplain::class);
    }

    public function komplain_tanggapan()
    {
        return $this->belongsTo(KomplainTanggapan::class);
    }

    public function pengelola()
    {
        return $this->belongsTo(Pengelola::class);
    }

    public function rusun()
    {
        return $this->belongsTo(Rusun::class);
    }
}
