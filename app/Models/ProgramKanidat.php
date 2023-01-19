<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKanidat extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public static $rules = [
        'grup_nama' => 'required|string|max:255',
        // 'rusun_unit_detail_id' => 'required|string',
        'rusun_detail_id' => 'required|string',
        'pemilik_penghuni_id' => 'required|string',
        'program_jabatan_id' => 'required|string',
        'program_id' => 'required|string',
        'rusun_id' => 'required|string',
    ];

    public static $ruleMessages = [
        'rusun_id' => 'rusun',
        'program_id' => 'program',
        'program_jabatan_id' => 'jabatan',
        'pemilik_penghuni_id' => 'pemilik/penghuni',
        'rusun_detail_id' => 'tower',
        'rusun_unit_detail_id' => 'unit',
    ];

    public function getGrupStatusTextAttribute()
    {
        switch ($this->grup_status) {
            case 0:
                return 'Belum Diverifikasi';
                break;

            case 1:
                return 'Diterima';
                break;

            case 2:
                return 'Tidak Memenuhi Syarat';
                break;
            
            default:
                return 'No Defined';
                break;
        }
    }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 0:
                return 'Belum Diverifikasi';
                break;

            case 1:
                return 'Diterima';
                break;

            case 2:
                return 'Tidak memenuhi syarat';
                break;

            case 3:
                return 'Mendaftar';
                break;

            case 4:
                return 'Didaftarkan Pengelola';
                break;

            case 5:
                return 'Didaftarkan Penghuni';
                break;

            case 6:
                return 'Menolak';
                break;
            
            default:
                return 'No Defined';
                break;
        }
    }

    public function getPemilikPenghuniProfileAttribute()
    {
        if ($this->apakah_pemilik) {
            return \App\Models\Pemilik::where('id', $this->pemilik_penghuni_id)->first();
        } else {
            return \App\Models\RusunPenghuni::where('id', $this->pemilik_penghuni_id)->first();
        }
    }

    public function members($grup_id, $programDokumens)
    {
        return ProgramKanidat::where('grup_id', $grup_id)
            ->get()
            ->map(function ($row) use ($programDokumens) {
                $kanidatDokumen = $row->program_kanidat_dokumens()->count();

                $row->dokumen = $kanidatDokumen == count($programDokumens) ? 'Sudah Dipenuhi' : 'Belum Dipenuhi';
                $row->profile = $row->pemilik_penghuni_profile;
                $row->status = $row->status_text;
                $row->rusun_detail;
                $row->rusun_unit_detail;
                $row->program_jabatan;

                return $row;
            });
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

    public function rusun_detail()
    {
        return $this->belongsTo(RusunDetail::class);
    }

    public function rusun_unit_detail()
    {
        return $this->belongsTo(RusunUnitDetail::class);
    }

    public function program_kanidat_dokumens()
    {
        return $this->hasMany(ProgramKanidatDokumen::class);
    }
}
