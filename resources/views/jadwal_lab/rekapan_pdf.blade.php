<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekapan Jadwal Lab - {{ $aslab->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }

        .info-section {
            margin: 15px 0;
            display: table;
            width: 100%;
        }

        .info-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }

        .statistics {
            margin: 15px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .stats-grid {
            display: table;
            width: 100%;
        }

        .stats-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 5px;
        }

        .stats-number {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }

        .stats-label {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>REKAPAN AKTIVITAS JADWAL LABORATORIUM</h1>
        <h2>Asisten Laboratorium: {{ $aslab->nama }}</h2>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <div class="info-left">
            <strong>Nama Aslab:</strong> {{ $aslab->nama }}<br>
            <strong>NIM/NIDN:</strong> {{ $aslab->nim_nidn }}<br>
            <strong>Hak Akses:</strong> {{ ucfirst($aslab->hak_akses) }}
        </div>
        <div class="info-right">
            <strong>Periode:</strong> {{ $periode }}<br>
            <strong>Total Aktivitas:</strong> {{ $totalAksi }}<br>
            <strong>Tanggal Cetak:</strong> {{ date("d F Y, H:i:s") }}
        </div>
    </div>

    <!-- Statistics -->
    <div class="statistics">
        <h3 style="margin: 0 0 10px 0; text-align: center;">Statistik Aktivitas</h3>
        <div class="stats-grid">
            <div class="stats-item">
                <div class="stats-number">{{ $totalAksi }}</div>
                <div class="stats-label">Total Aksi</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">{{ $statistikAksi["tambah"] ?? 0 }}</div>
                <div class="stats-label">Tambah Jadwal</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">{{ $statistikAksi["edit"] ?? 0 }}</div>
                <div class="stats-label">Edit Jadwal</div>
            </div>
            <div class="stats-item">
                <div class="stats-number">{{ $statistikAksi["ubah_status"] ?? 0 }}</div>
                <div class="stats-label">Ubah Status</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    @if ($dataRekapan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Tanggal</th>
                    <th width="8%">Waktu</th>
                    <th width="10%">Aksi</th>
                    <th width="15%">Ruang Lab</th>
                    <th width="12%">Jadwal</th>
                    <th width="15%">Dosen</th>
                    <th width="23%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataRekapan as $index => $rekapan)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($rekapan->tanggal_aksi)->locale("id")->translatedFormat("d M Y") }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($rekapan->waktu_aksi)->format("H:i:s") }}</td>
                        <td>
                            @switch($rekapan->aksi)
                                @case("tambah")
                                    <span class="badge badge-success">Tambah</span>
                                @break

                                @case("edit")
                                    <span class="badge badge-warning">Edit</span>
                                @break

                                @case("hapus")
                                    <span class="badge badge-danger">Hapus</span>
                                @break

                                @case("ubah_status")
                                    <span class="badge badge-info">Ubah Status</span>
                                @break

                                @default
                                    <span class="badge badge-secondary">{{ ucfirst($rekapan->aksi) }}</span>
                            @endswitch
                        </td>
                        <td>
                            @if ($rekapan->jadwalLab && $rekapan->jadwalLab->ruangLaboratorium)
                                {{ $rekapan->jadwalLab->ruangLaboratorium->nama_ruang }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($rekapan->jadwalLab)
                                {{ ucfirst($rekapan->jadwalLab->hari) }}<br>
                                {{ $rekapan->jadwalLab->waktu_mulai }}-{{ $rekapan->jadwalLab->waktu_selesai }}
                            @else
                                Data dihapus
                            @endif
                        </td>
                        <td>
                            @if ($rekapan->jadwalLab && $rekapan->jadwalLab->dosen)
                                {{ $rekapan->jadwalLab->dosen->nama_dosen }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($rekapan->keterangan)
                                {{ $rekapan->keterangan }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>Tidak Ada Data</h3>
            <p>Belum ada aktivitas jadwal lab yang tercatat untuk periode ini.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Laboratorium pada {{ date("d F Y, H:i:s") }}</p>
        <p>Universitas Sumatera Utara - Fakultas Ilmu Komputer dan Teknologi Informasi</p>
    </div>
</body>

</html>
