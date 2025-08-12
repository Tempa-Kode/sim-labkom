<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Semua Laboratorium</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 10px;
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
            width: 80px;
            text-align: center;
        }

        .header-table .content-cell {
            text-align: center;
            width: auto;
        }

        .header-table .empty-cell {
            width: 80px;
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
            font-size: 9px;
            color: #333;
        }

        .header-content span {
            font-size: 9px;
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
            font-size: 10px;
        }

        .lab-section {
            margin: 25px 0;
            border: 1px solid #000;
            padding: 10px;
            background-color: #f9f9f9;
            page-break-inside: avoid;
        }

        .lab-section h4 {
            margin: 0 0 15px 0;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            background-color: #000;
            color: #fff;
            padding: 8px;
            text-transform: uppercase;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            font-size: 8px;
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
            margin-top: 10px;
            padding: 8px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            font-size: 9px;
        }

        .footer {
            margin-top: 30px;
            font-size: 8px;
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
            font-size: 10px;
        }

        .page-break {
            page-break-before: always;
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
                <img src="{{ asset('assets/img/logo-fikom.png') }}" alt="Logo FIKOM" style="width: 70px; height: auto;">
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
        <h3>Laporan Data Inventaris Laboratorium</h3>
    </div>

    <!-- Report Information -->
    <table class="info-table">
        <tr>
            <td style="width: 120px;"><strong>Tanggal Cetak</strong></td>
            <td>: {{ date('d F Y, H:i:s') }}</td>
        </tr>
        <tr>
            <td><strong>Total Laboratorium</strong></td>
            <td>: {{ $ruangLab->count() }} Ruang</td>
        </tr>
        <tr>
            <td><strong>Total Inventaris</strong></td>
            <td>: {{ $ruangLab->sum(function($lab) { return $lab->inventaris->count(); }) }} Item</td>
        </tr>
        <tr>
            <td><strong>Periode</strong></td>
            <td>: {{ date('F Y') }}</td>
        </tr>
    </table>

    <!-- Data grouped by lab -->
    @forelse ($ruangLab as $index => $lab)
        @if($index > 0)
            <div class="page-break"></div>
        @endif

        <div class="lab-section">
            <h4>{{ $lab->nama_ruang }}</h4>

            @if($lab->keterangan)
                <p style="text-align: center; margin-bottom: 15px; font-style: italic;">{{ $lab->keterangan }}</p>
            @endif

            @if($lab->inventaris->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 25px;">No</th>
                            <th style="width: 80px;">Kode Barang</th>
                            <th style="width: 120px;">Nama Barang</th>
                            <th style="width: 60px;">Jenis</th>
                            <th style="width: 40px;">Jumlah</th>
                            <th style="width: 60px;">Kondisi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lab->inventaris as $itemIndex => $inventaris)
                            <tr>
                                <td>{{ $itemIndex + 1 }}</td>
                                <td>{{ $inventaris->kode_barang ?? '-' }}</td>
                                <td class="text-left">{{ $inventaris->nama_barang }}</td>
                                <td>{{ $inventaris->jenisInventaris->nama_jenis ?? '-' }}</td>
                                <td>{{ $inventaris->jumlah }}</td>
                                <td>{{ $inventaris->kondisi }}</td>
                                <td class="text-left">{{ $inventaris->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="summary">
                    <strong>Total Inventaris {{ $lab->nama_ruang }}: {{ $lab->inventaris->count() }} item</strong>
                    <br>
                    <strong>Total Barang: {{ $lab->inventaris->sum('jumlah') }} unit</strong>
                </div>
            @else
                <div class="text-center" style="padding: 20px;">
                    <p><strong>Belum ada data inventaris untuk {{ $lab->nama_ruang }}</strong></p>
                </div>
            @endif
        </div>
    @empty
        <div class="text-center">
            <p><strong>Tidak ada data laboratorium yang tersedia</strong></p>
        </div>
    @endforelse

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ date('d F Y, H:i:s') }}</p>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td class="signature-right">
                    <div class="signature-box">
                        <p>Medan, {{ date('d F Y') }}</p>
                        <p><strong>Kepala Laboratorium</strong></p>
                        <div style="height: 60px;"></div>
                        <p>Sardo Sipayung, S.Kom., M.Kom</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
