@extends('layout.index')

@section('judul', 'Laboratorium - SIM-LABKOM')

@section('konten')
    {{--Laboratorium--}}
    <div id="laboratorium" class="vh-lg-100 d-flex align-items-center justify-content-center bg-white">
        <div class="container">
            <h1 class="text-center">Ruang</h1>
            <h1 class="text-center">Laboratorium Komputer</h1>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4 mt-3">
                @forelse($dataJadwal as $jadwal)
                    <div class="col">
                        <div class="card h-100">
                            <h5 class="card-header text-center fw-bold bg-secondary text-white">{{ $jadwal->ruangLaboratorium->nama_ruang }}</h5>
                            <div class="card-body text-center mt-3">
                                <p class="card-text text-capitalize">{{ $jadwal->hari }}</p>
                                <p class="card-text">Mulai {{ $jadwal->waktu_mulai }}</p>
                                <p class="card-text">Selai {{ $jadwal->waktu_selesai }}</p>
                            </div>
                            <p class="text-center mt-1 text-muted">Dosen : {{ $jadwal->dosen->nama_dosen }}</p>
                            <button class="btn btn-{{ $jadwal->status_ruang == 'digunakan' ? 'success' : 'warning' }} btn-sm text-uppercase">
                                {{ $jadwal->status_ruang }}
                            </button>
                        </div>
                    </div>
                @empty
                    <h5 class="text-center text-muted">Tidak ada jadwal laboratorium yang tersedia</h5>
                    <p class="text-center text-muted">Silakan cek kembali nanti.</p>
                @endforelse
            </div>
        </div>
    </div>
    {{--Laboratorium--}}
@endsection
