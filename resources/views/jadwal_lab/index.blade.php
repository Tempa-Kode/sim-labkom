@extends('layout.app')

@section('judul', 'Jadwal Ruang Lab')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Jadwal Ruang Lab</h4>
        @include('komponen.alert')
        <div class="card">
            <h5 class="card-header">Jadwal Penggunaan Ruang Laboratorium</h5>
            <div class="px-3 mb-3">
                @if (Auth::user()->hak_akses == 'aslab')
                    <a href="{{ route('jadwalLab.tambah') }}" class="btn btn-primary">
                        <i class="fa-solid fa-square-plus me-2"></i>Tambah Jadwal
                    </a>
                @endif
                <div class="d-flex mt-3">
                    <a href="#" class="btn btn-success me-3">
                        <i class="fa-solid fa-file-excel me-2"></i>Excel
                    </a>
                    <a href="#" class="btn btn-danger">
                        <i class="fa-solid fa-file-pdf me-2"></i>PDF
                    </a>
                </div>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruang</th>
                            <th>Hari</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Waktu Dosen</th>
                            <th>Status</th>
                            @if (Auth::user()->hak_akses == 'aslab')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $jadwal)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jadwal->ruangLaboratorium->nama_ruang }}</td>
                                <td>{{ $jadwal->hari ?? "-" }}</td>
                                <td>{{ $jadwal->waktu_mulai ?? "-" }}</td>
                                <td>{{ $jadwal->waktu_selesai ?? "-" }}</td>
                                <td>{{ $jadwal->dosen->nama_dosen ?? "-" }}</td>
                                <td> 
                                    @switch($jadwal->status_ruang)
                                        @case('digunakan')
                                            <button type="button" class="btn btn-info btn-sm text-uppercase">{{ $jadwal->status_ruang }}</button>
                                            @break
                                        @default
                                            <button type="button" class="btn btn-warning btn-sm text-uppercase">{{ $jadwal->status_ruang }}</button>
                                    @endswitch
                                </td>
                                @if (Auth::user()->hak_akses == 'aslab')
                                    <td>
                                        <a href="{{ route('jadwalLab.edit', $jadwal->id) }}" class="btn btn-success btn-sm me-2">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('jadwalLab.hapus', $jadwal->id) }}" method="POST" class="d-inline">
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
