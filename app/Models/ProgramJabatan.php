<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramJabatan extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public static $rules = [
        'nama' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
        'rusun_id' => 'required|string',
    ];

    public static $ruleMessages = [
        'rusun_id' => 'rusun',
    ];

    public function rusun()
    {
        return $this->belongsTo(Rusun::class);
    }

    public function program_kanidats()
    {
        return $this->hasMany(ProgramKanidat::class);
    }
}
