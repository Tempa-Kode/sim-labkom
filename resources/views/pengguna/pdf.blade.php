<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Akun Dosen</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            margin: 0;
            padding: 15px;
            line-height: 1.4;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-table td {
            border: none;
            padding: 10px;
            vertical-align: middle;
        }

        .header-table .logo-cell {
            width: 100px;
            text-align: center;
        }

        .header-table .content-cell {
            text-align: center;
            width: auto;
        }

        .header-table .empty-cell {
            width: 100px;
        }

        .header-content h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }

        .header-content h2 {
            margin: 2px 0;
            font-size: 14px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }

        .header-content p {
            margin: 5px 0 2px 0;
            font-size: 10px;
            color: #333;
        }

        .header-content span {
            font-size: 10px;
            color: #333;
        }

        .divider {
            border: none;
            border-top: 2px solid #000;
            margin: 15px 0;
        }

        .report-title {
            text-align: center;
            margin: 20px 0;
        }

        .report-title h3 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .info-table {
            margin-bottom: 20px;
            border: none;
        }

        .info-table td {
            border: none;
            padding: 2px 0;
            font-size: 11px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-size: 10px;
        }

        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .data-table td.text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        .footer {
            margin-top: 30px;
            font-size: 9px;
            font-style: italic;
            text-align: center;
        }

        .signature-section {
            margin-top: 40px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            border: none;
            padding: 5px;
            vertical-align: top;
        }

        .signature-left {
            width: 50%;
            text-align: left;
        }

        .signature-right {
            width: 50%;
            text-align: center;
        }

        .signature-box {
            margin-top: 15px;
        }

        .signature-box p {
            margin: 0 0 5px 0;
            font-size: 11px;
        }

        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Header with Logo and Institution Info -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ asset("assets/img/logo-fikom.png") }}" alt="Logo FIKOM" style="width: 80px; height: auto;">
            </td>
            <td class="content-cell">
                <div class="header-content">
                    <h1>Fakultas Ilmu Komputer</h1>
                    <h2>Universitas Katolik Santo Thomas Medan</h2>
                    <p>Jl. Setia Budi No. 479, Tj. Sari, Kec. Medan Selayang, Kota Medan, Sumatera Utara 20133</p>
                    <span>No. Telp: (061) 8213344 | Email: fikom@ust.ac.id</span>
                </div>
            </td>
            <td class="empty-cell">
                <!-- Space for second logo if needed -->
            </td>
        </tr>
    </table>

    <hr class="divider">

    <!-- Report Title -->
    <div class="report-title">
        <h3>Laporan Data Akun Dosen</h3>
    </div>

    <!-- Report Information -->
    <table class="info-table">
        <tr>
            <td style="width: 120px;"><strong>Tanggal Cetak</strong></td>
            <td>: {{ date("d F Y, H:i:s") }}</td>
        </tr>
        <tr>
            <td><strong>Total Data</strong></td>
            <td>: {{ $akunDosen->count() }} Akun Dosen</td>
        </tr>
    </table>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 150px;">Nama Lengkap</th>
                <th style="width: 100px;">Username</th>
                <th style="width: 70px;">Hak Akses</th>
                {{-- <th>Status Akun</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($akunDosen as $index => $dosen)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $dosen->nama }}</td>
                    <td>{{ $dosen->username }}</td>
                    <td class="text-capitalize">{{ $dosen->hak_akses }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data akun dosen</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ date("d F Y, H:i:s") }}</p>
    </div>

</body>

</html>
