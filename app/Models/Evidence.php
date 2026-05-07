<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;
    protected $fillable = [
        'guru_id',
        'file',
        'description',
        'subject',
        'class',
        'tanggal',
        'status',
    ];

    public function guru()
    {

        return $this->belongsTo(Guru::class);
    }
}
