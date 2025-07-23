@extends('layout.app')

@section('judul', 'Jenis Inventaris')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Jenis Inventaris</h4>
        @include('komponen.alert')
        <div class="card">
            <h5 class="card-header">Daftar Jenis Inventaris</h5>
            <div class="px-3 mb-3">
                <a href="{{ route('jenisInventaris.tambah') }}" class="btn btn-primary">
                    <i class="fa-solid fa-square-plus me-2"></i>Tambah
                </a>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Jenis</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $jenis)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jenis->nama_jenis}}</td>
                                <td>{{ $jenis->keterangan ?? "-" }}</td>
                                <td>
                                    <a href="{{ route('jenisInventaris.edit', $jenis->id) }}" class="btn btn-success btn-sm me-2">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('jenisInventaris.hapus', $jenis->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
