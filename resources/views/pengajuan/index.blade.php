@php
    use Carbon\Carbon;
@endphp
@extends("layout.app")

@section("judul", "Riwayat Pengajuan")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y">
        @include("komponen.alert")
        <div class="card">
            <h5 class="card-header {{ Auth::user()->hak_akses == "aslab" ? "text-center" : "" }}">
                {{ Auth::user()->hak_akses == "aslab" ? "Pengajuan Penggunaan Ruang Laboratorium" : "Riwayat Pengajuan" }}
                <div class="d-flex mt-3">
                    <a href="{{ route("pengajuan.exportExcel") }}" class="btn btn-success me-3">
                        <i class="fa-solid fa-file-excel me-2"></i>Excel
                    </a>
                    <a href="{{ route("pengajuan.exportPdf") }}" class="btn btn-danger">
                        <i class="fa-solid fa-file-pdf me-2"></i>PDF
                    </a>
                </div>
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
                            <th>Keterangan</th>
                            @if (Auth::user()->hak_akses != "dosen")
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $pengajuan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pengajuan->ruang->nama_ruang }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pemakaian)->locale("id")->translatedFormat("l") }}
                                </td>
                                <td>{{ $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->locale("id")->translatedFormat("d F Y") : "-" }}
                                </td>
                                <td>{{ $pengajuan->tanggal_pemakaian ? \Carbon\Carbon::parse($pengajuan->tanggal_pemakaian)->locale("id")->translatedFormat("d F Y") : "-" }}
                                </td>
                                <td>{{ $pengajuan->jam_mulai ? \Carbon\Carbon::parse($pengajuan->jam_mulai)->format("H:i") : "-" }}
                                </td>
                                <td>{{ $pengajuan->jam_selesai ? \Carbon\Carbon::parse($pengajuan->jam_selesai)->format("H:i") : "-" }}
                                </td>
                                <td>{{ $pengajuan->dosen->nama_dosen ?? "-" }}</td>
                                <td>
                                    @php($isDosen = Auth::user()->hak_akses == "dosen")
                                    @if ($isDosen && $pengajuan->status === "disetujui")
                                        <span data-ket-countdown
                                            data-start="{{ $pengajuan->tanggal_pemakaian }}T{{ $pengajuan->jam_mulai ? \Carbon\Carbon::parse($pengajuan->jam_mulai)->format("H:i") : "00:00" }}:00"
                                            data-end="{{ $pengajuan->tanggal_pemakaian }}T{{ $pengajuan->jam_selesai ? \Carbon\Carbon::parse($pengajuan->jam_selesai)->format("H:i") : "00:00" }}:00">
                                            <span
                                                class="badge rounded-pill bg-label-success">{{ $pengajuan->status }}</span>
                                        </span>
                                    @else
                                        @switch($pengajuan->status)
                                            @case("disetujui")
                                                <span class="badge rounded-pill bg-label-success">{{ $pengajuan->status }}</span>
                                            @break

                                            @case("ditolak")
                                                <span class="badge rounded-pill bg-label-danger">{{ $pengajuan->status }}</span>
                                            @break

                                            @case("dibatalkan")
                                                <span class="badge rounded-pill bg-label-warning show-reason"
                                                    style="cursor: pointer;"
                                                    data-id="{{ $pengajuan->id }}">{{ $pengajuan->status }}</span>
                                            @break

                                            @default
                                                <span class="badge rounded-pill bg-label-info">{{ $pengajuan->status }}</span>
                                        @endswitch
                                    @endif
                                </td>

                                @if (Auth::user()->hak_akses != "dosen")
                                    <td>
                                        @if ($pengajuan->status == "menunggu")
                                            <button type="button" class="btn btn-sm btn-success setuju"
                                                data-id="{{ $pengajuan->id }}">Setuju</button>
                                            <button type="button" class="btn btn-sm btn-danger tolak"
                                                data-id="{{ $pengajuan->id }}">Tolak</button>
                                        @else
                                            -
                                        @endif
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

@push("scripts")
    <script type="text/javascript">
        // Countdown pada kolom Keterangan (hanya DOSEN, hanya selama waktu penggunaan, selain itu tampilkan status)
        (function() {
            function formatHMS(ms) {
                const s = Math.max(0, Math.floor(ms / 1000));
                const h = String(Math.floor(s / 3600)).padStart(2, '0');
                const m = String(Math.floor((s % 3600) / 60)).padStart(2, '0');
                const ss = String(s % 60).padStart(2, '0');
                return `${h}:${m}:${ss}`;
            }

            function isSameYMD(a, b) {
                return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b
                    .getDate();
            }

            function start() {
                document.querySelectorAll('[data-ket-countdown]').forEach(el => {
                    if (el.dataset.bound) return; // avoid double binding
                    el.dataset.bound = '1';
                    // Simpan konten status asli untuk dipulihkan di luar jam pakai
                    if (!el.dataset.original) el.dataset.original = el.innerHTML;
                    const startStr = el.getAttribute('data-start');
                    const endStr = el.getAttribute('data-end');
                    const startAt = new Date(startStr.replace(' ', 'T'));
                    const endAt = new Date(endStr.replace(' ', 'T'));
                    const today = new Date();
                    if (!(isFinite(startAt) && isFinite(endAt))) {
                        el.innerHTML = el.dataset.original;
                        return;
                    }
                    if (!isSameYMD(startAt, today)) {
                        el.innerHTML = el.dataset.original;
                        return;
                    }

                    function tick() {
                        const now = new Date();
                        if (now < startAt || now >= endAt) {
                            // Di luar rentang pemakaian: tampilkan status asli
                            el.innerHTML = el.dataset.original;
                            return;
                        }
                        // Dalam rentang pemakaian: tampilkan countdown
                        el.innerHTML =
                            `<span class="badge rounded-pill bg-label-info">${formatHMS(endAt - now)}</span>`;
                    }
                    tick();
                    const t = setInterval(tick, 1000);
                    el.addEventListener('remove', () => clearInterval(t));
                });
            }
            document.addEventListener('DOMContentLoaded', start);
        })();

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
                        url: "{{ url("/dashboard/pengajuan/setujui") }}/" + id,
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
                        url: "{{ url("/dashboard/pengajuan/tolak") }}/" + id,
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

        $('.batal').on('click', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Ajukan Pembatalan',
                text: "Silahkan berikan alasan pembatalan:",
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: 'Masukkan alasan pembatalan...',
                inputAttributes: {
                    'required': true
                },
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ajukan Pembatalan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan pembatalan harus diisi!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url("/dashboard/pengajuan/batalkan") }}/" + id,
                        type: 'POST',
                        data: {
                            _method: 'PUT',
                            _token: '{{ csrf_token() }}',
                            keterangan: result.value
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                Swal.fire(
                                    'Dibatalkan!',
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
                            console.log(xhr);
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat membatalkan pengajuan.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.show-reason', function() {
            var id = $(this).data('id');

            $.ajax({
                url: "{{ route("pengajuan.keterangan", ":id") }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Alasan ' + response.status.charAt(0).toUpperCase() +
                                response.status.slice(1),
                            text: response.keterangan,
                            icon: 'info',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Tutup'
                        });
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Gagal mengambil keterangan',
                        'error'
                    );
                }
            });
        });
    </script>
@endpush
