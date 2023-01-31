<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingKanidat extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public static $rules = [
        'grup_id' => 'required|string',
    ];

    public static $ruleMessages = [
        'grup_id' => 'grup kanidat',
    ];

    public function getPemilikPenghuniMemilihProfileAttribute()
    {
        if ($this->apakah_pemilik) {
            return \App\Models\Pemilik::where('id', $this->pemilik_penghuni_memilih)->first();
        } else {
            return \App\Models\RusunPenghuni::where('id', $this->pemilik_penghuni_memilih)->first();
        }
    }

    public function rusun()
    {
        return $this->belongsTo(Rusun::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function program_kanidat()
    {
        return $this->belongsTo(ProgramKanidat::class, 'grup_id', 'grup_id');
    }
}
