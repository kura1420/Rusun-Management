<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public static $rules = [
        'nama' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
        'file' => 'nullable|mimes:pdf',
        'periode' => 'required|numeric',
        'tahun' => 'required|numeric',
        'rusun_id' => 'required|string',
    ];

    public static $ruleMessages = [
        'rusun_id' => 'rusun',
    ];

    public function statusText($value)
    {
        switch ($value) {
            case 0:
                return 'Menunggu';
                break;

            case 1:
                return 'Selesai';
                break;

            case 2:
                return 'Proses';
                break;

            case 3:
                return 'Ditunda';
                break;
            
            default:
                return 'No Defined';
                break;
        }
    }

    public function rusun()
    {
        return $this->belongsTo(Rusun::class);
    }

    public function program_kegiatans()
    {
        return $this->hasMany(ProgramKegiatan::class)->orderBy('created_at');
    }

    public function program_laporans()
    {
        return $this->hasMany(ProgramLaporan::class);
    }

    public function program_kanidats()
    {
        return $this->hasMany(ProgramKanidat::class);
    }
}
