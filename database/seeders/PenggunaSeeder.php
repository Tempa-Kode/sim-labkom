<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama' => 'admin simlabkom',
            'username' => 'adminsimlabkom',
            'password' => bcrypt('12345678'),
            'hak_akses' => 'admin',
        ]);

        User::create([
            'nama' => 'aslab 1',
            'username' => 'aslab1',
            'password' => bcrypt('12345678'),
            'hak_akses' => 'aslab',
        ]);

        User::create([
            'nama' => 'aslab 2',
            'username' => 'aslab2',
            'password' => bcrypt('12345678'),
            'hak_akses' => 'aslab',
        ]);

        User::create([
            'nama' => 'aslab 3',
            'username' => 'aslab3',
            'password' => bcrypt('12345678'),
            'hak_akses' => 'aslab',
        ]);
    }
}
