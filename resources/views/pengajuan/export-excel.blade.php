<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pengajuan Laboratorium</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA PENGAJUAN PENGGUNAAN LABORATORIUM</h2>
        <h3>FAKULTAS ILMU KOMPUTER - UNIVERSITAS KATOLIK SANTO THOMAS MEDAN</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Ruang Lab</th>
                <th>Hari</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Pemakaian</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Nama Dosen</th>
                <th>Status</th>
                <th>Keterangan</th>
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
                    <td class="text-center">{{ ucfirst($pengajuan->status) }}</td>
                    <td>{{ $pengajuan->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data pengajuan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: center; font-size: 12px;">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y, H:i:s') }}</p>
        <p>© SIM-LABKOM FIKOM UNIKA Santo Thomas</p>
    </div>
</body>
</html>
