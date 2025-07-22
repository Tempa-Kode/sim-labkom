<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuangLaboratorium extends Model
{
    use HasFactory;

    protected $table = 'tb_ruang_lab';

    public $timestamps = false;

    protected $fillable = [
        'nama_ruang',
        'keterangan',
    ];

    public function jadwalLaboratorium()
    {
        return $this->hasMany(JadwalLaboratorium::class, 'id_ruang_lab');
    }

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'id_ruang');
    }
}
