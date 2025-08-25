<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapanJadwalLab extends Model
{
    use HasFactory;

    protected $table = 'tb_rekapan_jadwal_lab';

    protected $fillable = [
        'id_aslab',
        'id_jadwal_lab',
        'aksi',
        'keterangan',
        'tanggal_aksi',
        'waktu_aksi'
    ];

    protected $casts = [
        'tanggal_aksi' => 'date',
        'waktu_aksi' => 'datetime:H:i:s'
    ];

    /**
     * Relationship dengan User (Aslab)
     */
    public function aslab()
    {
        return $this->belongsTo(User::class, 'id_aslab');
    }

    /**
     * Relationship dengan JadwalLaboratorium
     */
    public function jadwalLab()
    {
        return $this->belongsTo(JadwalLaboratorium::class, 'id_jadwal_lab');
    }

    /**
     * Scope untuk filter berdasarkan aslab
     */
    public function scopeByAslab($query, $aslabId)
    {
        return $query->where('id_aslab', $aslabId);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByTanggal($query, $tanggalMulai, $tanggalSelesai = null)
    {
        if ($tanggalSelesai) {
            return $query->whereBetween('tanggal_aksi', [$tanggalMulai, $tanggalSelesai]);
        }
        return $query->where('tanggal_aksi', $tanggalMulai);
    }

    /**
     * Scope untuk filter mingguan
     */
    public function scopeMingguan($query, $tanggalMulai)
    {
        $tanggalSelesai = \Carbon\Carbon::parse($tanggalMulai)->addDays(6)->format('Y-m-d');
        return $query->whereBetween('tanggal_aksi', [$tanggalMulai, $tanggalSelesai]);
    }
}
