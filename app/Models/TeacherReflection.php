<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherReflection extends Model
{
    protected $fillable = [
        'guru_id',
        'evaluation_id',
        'reflection',
        'improvement_plan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
