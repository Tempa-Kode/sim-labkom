@extends('layout.app')

@section('judul', 'Inventaris Lab')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Inventaris</h4>
        @include('komponen.alert')
        <div class="card">
            <h5 class="card-header">Daftar Inventaris Laboratorium</h5>
            <div class="px-3 mb-3">
                @if (Auth::user()->hak_akses == 'aslab')
                    <a href="{{ route('inventaris.tambah') }}" class="btn btn-primary">
                        <i class="fa-solid fa-square-plus me-2"></i>Tambah
                    </a>
                @endif
                <div class="d-flex mt-3">
                    <a href="{{ route('inventaris.exportExcel') }}" class="btn btn-success me-3">
                        <i class="fa-solid fa-file-excel me-2"></i>Excel
                    </a>
                    <a href="{{ route('inventaris.exportPdf') }}" class="btn btn-danger">
                        <i class="fa-solid fa-file-pdf me-2"></i>PDF
                    </a>
                </div>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kode Barang</th>
                            <th>Kondisi</th>
                            <th>Ket</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Ruangan</th>
                            <th>Nama Petugas</th>
                            @if (Auth::user()->hak_akses == 'aslab')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $jadwal)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jadwal->nama_barang }}</td>
                                <td>{{ $jadwal->kode_barang ?? "-" }}</td>
                                <td>{{ $jadwal->kondisi }}</td>
                                <td>{{ $jadwal->keterangan ?? "-" }}</td>
                                <td>{{ $jadwal->jenisInventaris->nama_jenis }}</td>
                                <td>{{ $jadwal->jumlah }}</td>
                                <td>{{ $jadwal->ruangLaboratorium->nama_ruang }}</td>
                                <td>{{ $jadwal->aslab->nama }}</td>
                                @if (Auth::user()->hak_akses == 'aslab')
                                    <td>
                                        <a href="{{ route('inventaris.edit', $jadwal->id) }}" class="btn btn-success btn-sm me-2">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('inventaris.hapus', $jadwal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
