<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramLaporan extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public static $rules = [
        'judul' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'penjelasan' => 'required|string',
        'program_kegiatan_id' => 'required|string',
        'dokumentasis' => 'nullable|array|max:5',
        'dokumentasis.*' => 'nullable|mimes:jpeg,jpg,png,pdf|max:15000'
    ];

    public static $ruleMessages = [
        'rusun_id' => 'rusun',
        'program_id' => 'program',
        'program_kegiatan_id' => 'kegiatan',
    ];

    public function rusun()
    {
        return $this->belongsTo(Rusun::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function program_kegiatan()
    {
        return $this->belongsTo(ProgramKegiatan::class);
    }

    public function program_laporan_dokumens()
    {
        return $this->hasMany(ProgramLaporanDokumen::class);
    }
}
