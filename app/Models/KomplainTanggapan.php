<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomplainTanggapan extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function komplain()
    {
        return $this->belongsTo(Komplain::class);
    }

    public function komplain_files()
    {
        return $this->hasMany(KomplainFile::class);
    }

    public function pengelola()
    {
        return $this->belongsTo(Pengelola::class);
    }

    public function rusun()
    {
        return $this->belongsTo(Rusun::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ditanggapi_user_id');
    }
}
