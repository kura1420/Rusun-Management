<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKegiatan extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public static $rules = [
        'nama' => 'required|string|max:255',
        'tanggal_mulai' => 'required|date',
        'tanggal_berakhir' => 'required|date',
        'informasi' => 'nullable|string',
        'rusun_id' => 'required|string',
        'program_id' => 'required|string',
        'file' => 'nullable|mimes:pdf',
        'template' => 'nullable|string',
        'syarat_ketentuan' => 'nullable|string',
    ];

    public static $ruleMessages = [
        'rusun_id' => 'rusun',
        'program_id' => 'program',
        'template' => 'lampiran',
        'syarat_ketentuan' => 'syarat & ketentuan',
    ];

    public function getStatusTextAttribute($value)
    {
        switch ($value) {
            case 0:
                return 'Proses';
                break;

            case 1:
                return 'Sudah diverifikasi';
                break;

            case 2:
                return 'Ditolak';
                break;
            
            default:
                return 'No Defined';
                break;
        }
    }

    public function getTemplateTextAttribute()
    {
        switch ($this->template) {
            case 'form_pendaftaran':
                return 'Form Pendaftaran Kanidat';
                break;

            case 'polling':
                return 'Polling';
                break;

            case 'laporan':
                return 'Laporan';
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

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function program_laporans()
    {
        return $this->hasMany(ProgramLaporan::class);
    }
}
