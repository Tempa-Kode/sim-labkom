@extends('layout.app')

@section('judul', 'Data Absensi Aslab')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Absensi Aslab</h4>
        @include('komponen.alert')
        <div class="card">
            <h5 class="card-header">Daftar Absensi Aslab</h5>
            <div class="w-25 ps-4">
                <form id="filter-tanggal" action="" method="get">
                    <label for="tanggal">Filter Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control">
                </form>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Aslab</th>
                            <th>Hari</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $absensi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $absensi->aslab->nama }}</td>
                                <td>{{ $absensi->hari }}</td>
                                <td>{{ $absensi->tanggal }}</td>
                                <td>{{ $absensi->keterangan ?? "-" }}</td>
                                <td>
                                    <form action="{{ route('absensi.hapus', $absensi->id) }}" method="POST" class="d-inline">
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

@push('scripts')
    <script>
        $('#tanggal').on('change', function() {
           $('#filter-tanggal').submit();
        });
    </script>
@endpush
