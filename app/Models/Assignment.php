<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'penilai_id',
        'guru_id',
        'period_id',
    ];

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
