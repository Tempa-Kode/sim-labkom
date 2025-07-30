<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'tb_pengajuan';

    protected $fillable = [
        'id_dosen',
        'id_ruang',
        'tanggal_pengajuan',
        'tanggal_pemakaian',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    public $timestamps = false;

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    public function ruang()
    {
        return $this->belongsTo(RuangLaboratorium::class, 'id_ruang');
    }

}
