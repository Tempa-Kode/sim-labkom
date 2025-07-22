<?php

namespace Database\Seeders;

use App\Models\JenisInventaris as ModelsJenisInventaris;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisInventaris extends Seeder
{
    public function run()
    {
        ModelsJenisInventaris::create([
            'nama_jenis' => 'Komputer',
        ]);

        ModelsJenisInventaris::create([
            'nama_jenis' => 'Laptop',
        ]);

        ModelsJenisInventaris::create([
            'nama_jenis' => 'Monitor',
        ]);

        ModelsJenisInventaris::create([
            'nama_jenis' => 'AC',
        ]);
    }
}
