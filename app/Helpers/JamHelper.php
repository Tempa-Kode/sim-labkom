<?php

namespace App\Helpers;

class JamHelper
{
    /**
     * Mapping jam fakultas ke format waktu
     */
    private static $jamMapping = [
        'AB' => ['08:00', '09:40'],
        'ABC' => ['08:00', '10:30'],
        'CD' => ['09:50', '11:30'],
        'CDE' => ['09:50', '12:20'],
        'DE' => ['10:40', '12:20'],
        'DEF' => ['10:40', '13:10'],
        'EF' => ['11:40', '13:20'],
        'GH' => ['14:00', '15:40'],
        'GHI' => ['14:00', '16:30'],
        'IJ' => ['15:50', '17:30'],
        'IJK' => ['15:50', '18:20'],
        'JK' => ['16:40', '18:20'],
        'JKL' => ['16:40', '19:10'],
    ];

    /**
     * Konversi dari waktu 24 jam ke format jam fakultas
     */
    public static function waktuKeJam($waktuMulai, $waktuSelesai)
    {
        $mulai = str_replace(':', '.', substr($waktuMulai, 0, 5));
        $selesai = str_replace(':', '.', substr($waktuSelesai, 0, 5));

        foreach (self::$jamMapping as $kode => $waktu) {
            $jamMulai = str_replace(':', '.', $waktu[0]);
            $jamSelesai = str_replace(':', '.', $waktu[1]);

            if ($jamMulai === $mulai && $jamSelesai === $selesai) {
                return "$kode";
            }
        }

        // Jika tidak ada yang cocok, tampilkan format asli
        return "$mulai - $selesai";
    }

    /**
     * Konversi dari format jam fakultas ke waktu 24 jam
     */
    public static function jamKeWaktu($jamKode)
    {
        // Hilangkan prefix "Jam " jika ada
        $kode = str_replace('Jam ', '', $jamKode);

        if (isset(self::$jamMapping[$kode])) {
            return self::$jamMapping[$kode];
        }

        return null;
    }

    /**
     * Mendapatkan semua pilihan jam fakultas
     */
    public static function getAllJam()
    {
        $result = [];
        foreach (self::$jamMapping as $kode => $waktu) {
            $result[$kode] = [
                'kode' => $kode,
                'label' => "Jam $kode",
                'waktu_mulai' => $waktu[0],
                'waktu_selesai' => $waktu[1]
            ];
        }
        return $result;
    }

    /**
     * Format untuk ditampilkan
     */
    public static function formatJam($waktuMulai, $waktuSelesai)
    {
        return self::waktuKeJam($waktuMulai, $waktuSelesai);
    }
}
