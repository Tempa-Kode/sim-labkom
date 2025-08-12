<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Aslab</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
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

        .date-section {
            margin: 20px 0;
            border: 1px solid #000;
            padding: 8px;
            background-color: #f9f9f9;
        }

        .date-section h4 {
            margin: 0 0 10px 0;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            background-color: #000;
            color: #fff;
            padding: 5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-size: 10px;
        }

        .data-table th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        .data-table td.text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
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
                <img src="{{ asset('assets/img/logo-fikom.png') }}" alt="Logo FIKOM" style="width: 80px; height: auto;">
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
        <h3>Laporan Data Absensi Asisten Laboratorium</h3>
    </div>

    <!-- Report Information -->
    <table class="info-table">
        <tr>
            <td style="width: 120px;"><strong>Periode</strong></td>
            <td>: {{ $periode }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Cetak</strong></td>
            <td>: {{ date('d F Y, H:i:s') }}</td>
        </tr>
        <tr>
            <td><strong>Total Data</strong></td>
            <td>: {{ $groupedData->sum(function($items) { return $items->count(); }) }} Record Absensi</td>
        </tr>
        <tr>
            <td><strong>Jumlah Hari</strong></td>
            <td>: {{ $groupedData->count() }} Hari</td>
        </tr>
    </table>

    <!-- Data grouped by date -->
    @forelse ($groupedData as $tanggal => $absensiPerTanggal)
        <div class="date-section">

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th style="width: 150px;">Nama Aslab</th>
                        <th style="width: 80px;">Hari</th>
                        <th style="width: 100px;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensiPerTanggal as $index => $absensi)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-left">{{ $absensi->aslab->nama }}</td>
                            <td>{{ $absensi->hari }}</td>
                            <td class="text-left">{{ $absensi->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary">
                <strong>Total Absensi pada {{ date('d/m/Y', strtotime($tanggal)) }}: {{ $absensiPerTanggal->count() }} orang</strong>
            </div>
        </div>
    @empty
        <div class="text-center">
            <p><strong>Tidak ada data absensi untuk periode yang dipilih</strong></p>
        </div>
    @endforelse

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ date('d F Y, H:i:s') }}</p>
    </div>
</body>

</html>
