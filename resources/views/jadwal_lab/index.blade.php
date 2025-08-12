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
                    <a href="{{ route('jadwalLab.exportExcel') }}" class="btn btn-success me-3">
                        <i class="fa-solid fa-file-excel me-2"></i>Excel
                    </a>
                    <a href="{{ route('jadwalLab.exportPdf') }}" class="btn btn-danger">
                        <i class="fa-solid fa-file-pdf me-2"></i>PDF
                    </a>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <select class="form-select" name="hari" id="hari">
                            <option value="senin">Senin</option>
                            <option value="selasa">Selasa</option>
                            <option value="rabu">Rabu</option>
                            <option value="kamis">Kamis</option>
                            <option value="jumat">Jumat</option>
                        </select>
                    </div>
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
                            <th>Nama Dosen</th>
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

@push('scripts')
    <script type="text/javascript">
        var table = $('#datatables').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        // Filter berdasarkan hari
        $('#hari').on('change', function() {
            var selectedDay = this.value;

            if (selectedDay === '') {
                // Jika "Semua Hari" dipilih, tampilkan semua data
                table.column(2).search('').draw();
            } else {
                // Filter berdasarkan hari yang dipilih (kolom ke-3, index 2)
                table.column(2).search('^' + selectedDay + '$', true, false).draw();
            }

            // Update nomor urut setelah filter
            table.on('draw', function() {
                table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            });
        });
    </script>
@endpush
