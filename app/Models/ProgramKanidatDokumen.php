<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKanidatDokumen extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public static $rules = [
        'file' => 'mimes:pdf',
        'program_dokumen_id' => 'required|string',
        'program_kanidat_id' => 'required|string',
    ];

    public static $ruleMessages = [
        'program_dokumen_id' => 'dokumen',
        'program_kanidat_id' => 'kanidat',
    ];

    public function getPemilikPenghuniProfileAttribute()
    {
        if ($this->apakah_pemilik) {
            return \App\Models\Pemilik::where('id', $this->pemilik_penghuni_id)->first();
        } else {
            return \App\Models\RusunPenghuni::where('id', $this->pemilik_penghuni_id)->first();
        }
    }

    public function program_dokumen()
    {
        return $this->belongsTo(ProgramDokumen::class);
    }

    public function program_kanidat()
    {
        return $this->belongsTo(ProgramKanidat::class);
    }

    public function rusun_unit_detail()
    {
        return $this->belongsTo(RusunUnitDetail::class);
    }

    public function rusun_detail()
    {
        return $this->belongsTo(RusunDetail::class);
    }

    public function rusun()
    {
        return $this->belongsTo(Rusun::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function program_jabatan()
    {
        return $this->belongsTo(ProgramJabatan::class);
    }
}
