<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Inventaris Laboratorium</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
        .footer-watermark {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #888;
            z-index: 1000;
        }
        #header {
            text-align: center;
            line-height: 0.5;
            margin-bottom: 20px;
        }
        .no-border,
        .no-border td,
        .no-border th {
            border: none !important;
        }
    </style>
</head>

<body>
<table class="no-border">
    <thead>
    <tr>
        <td>
            <img src="{{ asset('assets/img/logo-fikom.png') }}" alt="logo unika" style="width: 100px; height: auto;">
        </td>
        <td>
            <div id="header">
                <h1>FAKULTAS ILMU KOMPUTER</h1>
                <h2>UNIVERSITAS KATOLIK SANTO THOMAS MEDAN</h2>
                <p>Jl. Setia Budi No. 479, Tj. Sari, Kec. Medan Selayang, Kota Medan, Sumatera Utara 20133</p>
                <span>No. Telp : xxxxxxxxxxx </span> <span>Email : fikom@ust.ac.id</span>
            </div>
        </td>
        <td>
            {{-- <img src="{{ asset('assets/img/logo-unika.png') }}" alt="logo unika" style="width: 100px; height: auto;"> --}}
        </td>
    </tr>
    </thead>
</table>
<hr>
<h1 style="text-align: center">Data Inventaris</h1>

<table class="no-border">
    <tr>
        <td width="100" style="text-align: left">Kampus</td>
        <td width="10">:</td>
        <td style="text-align: left">Universitas Katolik Santo Thomas</td>
    </tr>
    <tr>
        <td width="100" style="text-align: left">Gedung</td>
        <td width="10">:</td>
        <td style="text-align: left">Fakultas Ilmu Komputer</td>
    </tr>
    <tr>
        <td width="100" style="text-align: left">Ruang Laboratorium</td>
        <td width="10">:</td>
        <td style="text-align: left">{{ $ruangLab->nama_ruang }}</td>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Nama Barang</th>
        <th>Kode Barang</th>
        <th>Kondisi</th>
        <th>Keterangan</th>
        <th>Jenis</th>
        <th>Jumlah</th>
        <th>Ruangan</th>
    </tr>
    </thead>
    <tbody>
    @forelse($data as $i => $jadwal)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $jadwal->nama_barang }}</td>
            <td>{{ $jadwal->kode_barang ?? "-" }}</td>
            <td>{{ $jadwal->kondisi }}</td>
            <td>{{ $jadwal->keterangan ?? "-" }}</td>
            <td>{{ $jadwal->jenisInventaris->nama_jenis }}</td>
            <td>{{ $jadwal->jumlah }}</td>
            <td>{{ $jadwal->ruangLaboratorium->nama_ruang }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8">Tidak ada data inventaris.</td>
        </tr>
    @endforelse
    </tbody>
</table>
<div style="margin-top: 60px; display: flex; justify-content: flex-end;">
    <div style="text-align: right;">
        <p>Diketahui Oleh,</p>
        <p style="margin-bottom: 70px;">Kepala Lab</p>
        <p style="margin-top: 0; font-weight: bold; text-decoration: underline;">Sardo Sipayung, S.Kom., M.Kom</p>
    </div>
</div>
<div class="footer-watermark">
    SIM-LABKOM | Dicetak pada tanggal {{ date("d-m-Y") }}
</div>
</body>

</html>
