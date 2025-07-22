<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        Dosen::create([
            'nama_dosen' => 'Wasit Ginting, S.Kom., M.Kom.',
        ]);

        Dosen::create([
            'nama_dosen' => 'Desinta Purba, S.T., M.Kom.',
        ]);

        Dosen::create([
            'nama_dosen' => 'Sorang Pakpahan, S.Kom., M.Kom.',
        ]);
    }
}
