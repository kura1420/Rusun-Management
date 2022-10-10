<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PengelolaKontak extends Model
{
    use HasFactory, Uuid, Notifiable;

    public $incrementing = false;

    protected $guarded = [];

    public function pengelolas()
    {
        return $this->belongsTo(Pengelola::class, 'pengelola_id');
    }
}
