<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfAssessment extends Model
{
    protected $fillable = [
        'guru_id',
        'period_id',
        'status',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function scores()
    {
        return $this->hasMany(
            SelfAssessmentScore::class
        );
    }
}