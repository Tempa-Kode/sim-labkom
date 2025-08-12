<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pengajuan Laboratorium</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        #header {
            text-align: center;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        #header h1 {
            font-size: 18px;
            margin: 8px 0;
            font-weight: bold;
            line-height: 1.1;
        }

        #header h2 {
            font-size: 16px;
            margin: 6px 0;
            font-weight: bold;
            line-height: 1.1;
        }

        #header p {
            font-size: 12px;
            margin: 6px 0;
            line-height: 1.2;
        }

        #header span {
            font-size: 11px;
            line-height: 1.3;
        }

        .no-border,
        .no-border td,
        .no-border th {
            border: none !important;
        }

        .report-title {
            text-align: center;
            margin: 20px 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .filter-info h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #495057;
        }

        .filter-item {
            margin-bottom: 5px;
            font-size: 12px;
        }

        .filter-item strong {
            color: #212529;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px 4px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-disetujui {
            background-color: #d4edda;
            color: #155724;
        }

        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-menunggu {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-dibatalkan {
            background-color: #fff3cd;
            color: #856404;
        }

        .footer-watermark {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #888;
            z-index: 1000;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #495057;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-row {
            display: table-row;
        }

        .summary-cell {
            display: table-cell;
            padding: 5px 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-cell:first-child {
            font-weight: bold;
            width: 30%;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <table class="no-border">
        <thead>
            <tr>
                <td style="width: 20%;">
                    <img src="{{ asset('assets/img/logo-fikom.png') }}" alt="logo fikom" style="width: 100px; height: auto;">
                </td>
                <td style="width: 60%;">
                    <div id="header">
                        <h1>FAKULTAS ILMU KOMPUTER</h1>
                        <h2>UNIVERSITAS KATOLIK SANTO THOMAS MEDAN</h2>
                        <p>Jl. Setia Budi No. 479, Tj. Sari, Kec. Medan Selayang, Kota Medan, Sumatera Utara 20133</p>
                        <span>No. Telp : (061) 8214413 </span> <span>Email : fikom@ust.ac.id</span>
                    </div>
                </td>
                <td style="width: 20%;">
                    {{-- Logo kedua jika diperlukan --}}
                </td>
            </tr>
        </thead>
    </table>
    <hr style="border: 2px solid #000; margin: 10px 0;">

    <!-- Judul Laporan -->
    <div class="report-title">
        Laporan Data Pengajuan Penggunaan Laboratorium
    </div>

    @if($tanggal_mulai || $tanggal_selesai || $status)
    <div class="filter-info">
        <h3>Filter yang Diterapkan:</h3>
        @if($tanggal_mulai)
            <div class="filter-item">
                <strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}
            </div>
        @endif
        @if($tanggal_selesai)
            <div class="filter-item">
                <strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}
            </div>
        @endif
        @if($status)
            <div class="filter-item">
                <strong>Status:</strong> {{ ucfirst($status) }}
            </div>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">Ruang Lab</th>
                <th style="width: 8%;">Hari</th>
                <th style="width: 12%;">Tgl Pengajuan</th>
                <th style="width: 12%;">Tgl Pemakaian</th>
                <th style="width: 8%;">Waktu Mulai</th>
                <th style="width: 8%;">Waktu Selesai</th>
                <th style="width: 15%;">Nama Dosen</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 11%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $pengajuan)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $pengajuan->ruang->nama_ruang ?? '-' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pemakaian)->locale('id')->translatedFormat('l') }}</td>
                    <td class="text-center">{{ $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->locale('id')->translatedFormat('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $pengajuan->tanggal_pemakaian ? \Carbon\Carbon::parse($pengajuan->tanggal_pemakaian)->locale('id')->translatedFormat('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $pengajuan->jam_mulai ? \Carbon\Carbon::parse($pengajuan->jam_mulai)->format('H:i') : '-' }}</td>
                    <td class="text-center">{{ $pengajuan->jam_selesai ? \Carbon\Carbon::parse($pengajuan->jam_selesai)->format('H:i') : '-' }}</td>
                    <td>{{ $pengajuan->dosen->nama_dosen ?? '-' }}</td>
                    <td class="text-center">
                        <span class="status-badge status-{{ $pengajuan->status }}">
                            {{ ucfirst($pengajuan->status) }}
                        </span>
                    </td>
                    <td>{{ $pengajuan->keterangan ? Str::limit($pengajuan->keterangan, 50) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data pengajuan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-watermark">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y, H:i:s') }} |
           Halaman <span class="pagenum"></span> |
           © SIM-LABKOM FIKOM UNIKA Santo Thomas</p>
    </div>
</body>
</html>
