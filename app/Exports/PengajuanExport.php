<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class PengajuanExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function export()
    {
        $query = Pengajuan::with(['ruang', 'dosen'])->orderBy('tanggal_pengajuan', 'desc');

        // Filter berdasarkan hak akses
        if (Auth::user()->hak_akses == 'dosen') {
            $query->where('id_dosen', Auth::user()->dosen->id);
        }

        // Filter berdasarkan tanggal jika ada
        if (isset($this->filters['tanggal_mulai']) && $this->filters['tanggal_mulai']) {
            $query->whereDate('tanggal_pengajuan', '>=', $this->filters['tanggal_mulai']);
        }

        if (isset($this->filters['tanggal_selesai']) && $this->filters['tanggal_selesai']) {
            $query->whereDate('tanggal_pengajuan', '<=', $this->filters['tanggal_selesai']);
        }

        // Filter berdasarkan status jika ada
        if (isset($this->filters['status']) && $this->filters['status']) {
            $query->where('status', $this->filters['status']);
        }

        $data = $query->get();

        // Generate CSV content
        $csvContent = $this->generateCSV($data);

        return $csvContent;
    }

    private function generateCSV($data)
    {
        $output = fopen('php://temp', 'w');

        // Header row
        $headers = [
            'No',
            'Ruang Lab',
            'Hari',
            'Tanggal Pengajuan',
            'Tanggal Pemakaian',
            'Waktu Mulai',
            'Waktu Selesai',
            'Nama Dosen',
            'Status',
            'Keterangan'
        ];

        fputcsv($output, $headers);

        // Data rows
        $no = 1;
        foreach ($data as $pengajuan) {
            $row = [
                $no++,
                $pengajuan->ruang->nama_ruang ?? '-',
                \Carbon\Carbon::parse($pengajuan->tanggal_pemakaian)->locale('id')->translatedFormat('l'),
                $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') : '-',
                $pengajuan->tanggal_pemakaian ? \Carbon\Carbon::parse($pengajuan->tanggal_pemakaian)->locale('id')->translatedFormat('d F Y') : '-',
                $pengajuan->jam_mulai ? \Carbon\Carbon::parse($pengajuan->jam_mulai)->format('H:i') : '-',
                $pengajuan->jam_selesai ? \Carbon\Carbon::parse($pengajuan->jam_selesai)->format('H:i') : '-',
                $pengajuan->dosen->nama_dosen ?? '-',
                ucfirst($pengajuan->status),
                $pengajuan->keterangan ?? '-'
            ];

            fputcsv($output, $row);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }
}
