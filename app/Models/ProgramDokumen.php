<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramDokumen extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public static $rules = [
        'nama' => 'required|string|max:255',
        'rusun_id' => 'required|string',
        'program_id' => 'required|string',
    ];

    public static $ruleMessages = [
        'rusun_id' => 'rusun',
        'program_id' => 'program',
    ];

    public function rusun()
    {
        return $this->belongsTo(Rusun::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function program_kanidat_dokumens()
    {
        return $this->hasMany(ProgramKanidatDokumen::class);
    }
}
