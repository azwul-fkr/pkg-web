<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;
    protected $fillable = [
        'sub_kriteria_id',
        'name',
    ];

    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class);
    }

    public function indikatorScores()
    {
        return $this->hasMany(IndikatorScore::class);
    }
}
