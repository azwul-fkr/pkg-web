<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;
    protected $table = 'evidences';
    // protected $fillable = [
    //     'guru_id',
    //     'file',
    //     'description',
    //     'subject',
    //     'kelas',
    //     'tanggal',
    //     'status',
    //     'kriteria_id',
    //     'sub_kriteria_id',
    //     'indikator_id',
    // ];
    protected $guarded = [];

    public function guru()
    {

        return $this->belongsTo(Guru::class);
    }
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
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
