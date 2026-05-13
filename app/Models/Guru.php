<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'jabatan_id',
        'nip',
        'phone',
        'address',
        'subject',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function evidences()
    {
        return $this->hasMany(Evidence::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
