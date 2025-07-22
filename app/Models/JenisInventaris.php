<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisInventaris extends Model
{
    use HasFactory;

    protected $table = 'tb_jenis';

    public $timestamps = false;

    protected $fillable = [
        'nama_jenis',
        'keterangan',
    ];

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'id_jenis');
    }
}
