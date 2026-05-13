<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorScore extends Model
{
    use HasFactory;
    protected $fillable = [
        'indikator_id',
        'score',
        'description',
    ];
    public function indikator()
    {
        return $this->belongsTo(Indikator::class);
    }
}
