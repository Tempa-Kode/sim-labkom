<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalLaboratorium extends Model
{
    use HasFactory;

    protected $table = 'tb_jadwal_lab';

    public $timestamps = false;

    protected $fillable = [
        'id_ruang_lab',
        'hari',
        'waktu_mulai',
        'waktu_selesai',
        'id_dosen',
        'status_ruang',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    public function ruangLaboratorium()
    {
        return $this->belongsTo(RuangLaboratorium::class, 'id_ruang_lab');
    }

    public function scopeFilterHari($query, $hari)
    {
        if ($hari) {
            $query->where('hari', $hari);
        }
        return $query;
    }

    public function scopeFilterWaktu($query, $waktu)
    {
        if ($waktu) {
            // Filter waktu di antara waktu_mulai dan waktu_selesai
            $query->where('waktu_mulai', '<=', $waktu)
                ->where('waktu_selesai', '>=', $waktu);
        }
        return $query;
    }
}
