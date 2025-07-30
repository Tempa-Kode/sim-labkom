@php
    use Carbon\Carbon;
@endphp
@extends('layout.app')

@section('judul', 'Riwayat Pengajuan')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('komponen.alert')
        <div class="card">
            <h5 class="card-header {{ Auth::user()->hak_akses == 'aslab' ? 'text-center' : '' }}">
                {{ Auth::user()->hak_akses == 'aslab' ? 'Pengajuan Penggunaan Ruang Laboratorium' : 'Riwayat Pengajuan' }}
            </h5>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ruang Lab</th>
                            <th>Hari</th>
                            <th>Tgl Pengajuan</th>
                            <th>Tgl Pemakaian</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Nama Dosen</th>
                            @if(Auth::user()->hak_akses == 'dosen')
                                <th>Keterangan</th>
                            @else
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $pengajuan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pengajuan->ruang->nama_ruang }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pemakaian)->locale('id')->translatedFormat('l') }}</td>
                                <td>{{ $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') : "-" }}</td>
                                <td>{{ $pengajuan->tanggal_pemakaian ? \Carbon\Carbon::parse($pengajuan->tanggal_pemakaian)->locale('id')->translatedFormat('d F Y') : "-" }}</td>
                                <td>{{ $pengajuan->jam_mulai ? \Carbon\Carbon::parse($pengajuan->jam_mulai)->format('H:i') : "-" }}</td>
                                <td>{{ $pengajuan->jam_selesai ? \Carbon\Carbon::parse($pengajuan->jam_selesai)->format('H:i') : "-" }}</td>
                                <td>{{ $pengajuan->dosen->nama_dosen ?? "-" }}</td>
                                @if (Auth::user()->hak_akses == 'dosen')
                                    <td>
                                        @switch($pengajuan->status)
                                            @case('disetujui')
                                                <span class="badge rounded-pill bg-label-success">{{  $pengajuan->status }}</span>
                                                @break
                                            @case('ditolak')
                                                <span class="badge rounded-pill bg-label-danger">{{  $pengajuan->status }}</span>
                                                @break
                                            @default
                                                <span class="badge rounded-pill bg-label-warning">{{  $pengajuan->status }}</span>
                                        @endswitch
                                    </td>
                                @else
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success setuju" data-id="{{ $pengajuan->id }}">Setuju</button>
                                        <button type="button" class="btn btn-sm btn-danger tolak" data-id="{{ $pengajuan->id }}">Tolak</button>
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
        $('.setuju').on('click', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menyetujui pengajuan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/dashboard/pengajuan/setujui') }}/" + id,
                        type: 'POST',
                        data: {
                            _method: 'PUT',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Disetujui!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menyetujui pengajuan.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('.tolak').on('click', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menolak pengajuan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/dashboard/pengajuan/tolak') }}/" + id,
                        type: 'POST',
                        data: {
                            _method: 'PUT',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Ditolak!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menolak pengajuan.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
