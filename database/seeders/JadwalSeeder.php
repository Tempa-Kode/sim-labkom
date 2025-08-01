<?php

namespace Database\Seeders;

use App\Models\JadwalLaboratorium;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/data-jadwal-lab.csv"), "r");
        while (($record = fgetcsv($input_file, 2000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $jadwal = array(
                    "id_ruang_lab" => $record['4'],
                    "hari" => $record['1'],
                    "waktu_mulai" => $record['6'],
                    "waktu_selesai" => $record['7'],
                    "id_dosen" => $record['2'],
                );
                JadwalLaboratorium::create($jadwal);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
