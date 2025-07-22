<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'tb_inventaris';

    public $timestamps = false;

    protected $fillable = [
        'nama_barang',
        'kondisi',
        'keterangan',
        'id_jenis',
        'jumlah',
        'id_ruang',
        'id_pengguna',
    ];

    public function jenisInventaris()
    {
        return $this->belongsTo(JenisInventaris::class, 'id_jenis');
    }

    public function ruangLaboratorium()
    {
        return $this->belongsTo(RuangLaboratorium::class, 'id_ruang');
    }

    public function aslab()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
