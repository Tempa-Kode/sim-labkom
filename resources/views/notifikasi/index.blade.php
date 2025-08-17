@extends("layout.app")

@section("judul", "Notifikasi")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Notifikasi</h4>
        @include("komponen.alert")
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Notifikasi</h5>
                <a href="{{ route("notifikasi.tambah") }}" class="btn btn-primary btn-sm"><i
                        class="fa-solid fa-plus me-2"></i>Tambah</a>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Pesan</th>
                            <th>Jadwal/Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $i => $n)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $n->judul }}</td>
                                <td>{{ $n->pesan }}</td>
                                <td>
                                    @if ($n->jadwal)
                                        {{ $n->jadwal->ruangLaboratorium->nama_ruang ?? '-' }}<br>
                                        {{ $n->jadwal->hari }} {{ $n->jadwal->waktu_mulai }}-{{ $n->jadwal->waktu_selesai }}
                                    @elseif ($n->pengajuan)
                                        [Pengajuan]
                                        {{ $n->pengajuan->ruang->nama_ruang ?? '-' }}<br>
                                        {{ \Carbon\Carbon::parse($n->pengajuan->tanggal_pemakaian)->locale('id')->translatedFormat('l, d F Y') }}<br>
                                        {{ $n->pengajuan->jam_mulai }}-{{ $n->pengajuan->jam_selesai }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><span
                                        class="badge bg-{{ $n->status === "baru" ? "warning" : "success" }}">{{ $n->status }}</span>
                                </td>
                                <td>
                                    @if ($n->pengajuan && $n->status === 'baru')
                                        <form action="{{ route('notifikasi.konfirmasiPengajuan', $n) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-primary btn-sm">Konfirmasi Pengajuan</button>
                                        </form>
                                    @endif
                                    <form action="{{ route("notifikasi.dibaca", $n) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method("PUT")
                                        <button class="btn btn-success btn-sm">Tandai Dibaca</button>
                                    </form>
                                    <form action="{{ route("notifikasi.hapus", $n) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method("DELETE")
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada notifikasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
