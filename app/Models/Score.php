<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;
    protected $table = 'scores';
    protected $guarded = [];
    

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class);
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class);
    }
}
