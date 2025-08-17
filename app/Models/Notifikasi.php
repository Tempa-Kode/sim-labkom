<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'tb_notifikasi';

    protected $fillable = [
        'jadwal_id',
    'pengajuan_id',
        'judul',
        'pesan',
        'status',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalLaboratorium::class, 'jadwal_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }
}
