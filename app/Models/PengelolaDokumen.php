<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengelolaDokumen extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 0:
                return "Belum di verifikasi";
                break;

            case 1:
                return "Diterima";
                break;

            case 2:
                return "Ditolak";
                break;
            
            default:
                return "No defined";
                break;
        }
    }

    public function pengelolas()
    {
        return $this->belongsTo(Pengelola::class, 'pengelola_id');
    }

    public function dokumens()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }

    public function rusuns()
    {
        return $this->belongsTo(Rusun::class, 'rusun_id');
    }
}
