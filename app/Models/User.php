<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tb_pengguna';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'hak_akses',
        'foto',
    ];

    public $timestamps = false;

    protected $hidden = [
        'password',
    ];

    public function absensiAslab()
    {
        return $this->hasMany(AbsensiAslab::class, 'id_pengguna');
    }

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'id_pengguna');
    }

    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'id_pengguna');
    }
}
