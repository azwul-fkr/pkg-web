<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfAssessmentScore extends Model
{
    protected $fillable = [
        'self_assessment_id',
        'indikator_id',
        'nilai',
        'comment',
    ];

    public function selfAssessment()
    {
        return $this->belongsTo(
            SelfAssessment::class
        );
    }

    public function indikator()
    {
        return $this->belongsTo(
            Indikator::class
        );
    }
}
