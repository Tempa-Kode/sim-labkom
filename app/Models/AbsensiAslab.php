<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiAslab extends Model
{
    use HasFactory;

    protected $table = 'tb_absensi';

    protected $fillable = [
        'id_pengguna',
        'hari',
        'tanggal',
        'keterangan',
    ];

    public $timestamps = false;

    public function aslab()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
