<?php

namespace Database\Seeders;

use App\Models\RuangLaboratorium;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuangLabSeeder extends Seeder
{
    public function run()
    {
        RuangLaboratorium::create([
            'nama_ruang' => 'LAB A',
        ]);
        RuangLaboratorium::create([
            'nama_ruang' => 'LAB B',
        ]);
        RuangLaboratorium::create([
            'nama_ruang' => 'LAB C',
        ]);
        RuangLaboratorium::create([
            'nama_ruang' => 'LAB D',
        ]);
        RuangLaboratorium::create([
            'nama_ruang' => 'LAB E',
        ]);
        RuangLaboratorium::create([
            'nama_ruang' => 'LAB F',
        ]);
    }
}
