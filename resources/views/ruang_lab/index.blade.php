@extends('layout.app')

@section('judul', 'Ruang Lab')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Ruang Lab</h4>
        @include('komponen.alert')
        <div class="card">
            <h5 class="card-header">Daftar Ruang Lab</h5>
            <div class="px-3 mb-3">
                <a href="{{ route('ruangLab.tambah') }}" class="btn btn-primary">
                    <i class="fa-solid fa-square-plus me-2"></i>Tambah
                </a>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruang</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $ruang)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ruang->nama_ruang }}</td>
                                <td>{{ $ruang->keterangan ?? "-" }}</td>
                                <td>
                                    <a href="{{ route('ruangLab.edit', $ruang->id) }}" class="btn btn-success btn-sm me-2">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('ruangLab.hapus', $ruang->id) }}" method="POST" class="d-inline">
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
