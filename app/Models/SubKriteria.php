<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;
    protected $fillable = [
        'kriteria_id',
        'kode',
        'name',
        'bobot',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function indikators()
    {
        return $this->hasMany(Indikator::class);
    }
}
