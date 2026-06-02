<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'guru_id',
        'user_id',
        'period_id',
        'status',
        'guru_id',
        'user_id',
        'period_id',
        'status',
        'revision_note',
        'feedback',
        'recommendation',
    ];
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->penilai();
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
