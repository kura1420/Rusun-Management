<?php

namespace App\Models;

use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomplainUserBuka extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $guarded = [];

    public function getWaktuFormatAttribute()
    {
        return Carbon::parse($this->waktu)->diffForHumans();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
