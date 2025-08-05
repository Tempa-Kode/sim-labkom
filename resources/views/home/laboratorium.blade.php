@extends('layout.index')

@section('judul', 'Laboratorium - SIM-LABKOM')

@section('konten')
    {{--Laboratorium--}}
    <div id="laboratorium" class="vh-lg-100 d-flex align-items-center justify-content-center bg-white">
        <div class="container">
            <h1 class="text-center">Ruang</h1>
            <h1 class="text-center">Laboratorium Komputer</h1>
            <h3 class="text-center">
                {{ $tanggalHariIni }}
            </h3>
            <div class="d-flex justify-content-center mt-4">
                <form action="" class="d-flex align-items-center gap-3 flex-nowrap flex-wrap">
                    <select name="hari" id="hari" class="form-select" style="min-width: 150px;" onchange="this.form.submit()">
                        <option value="" hidden>Pilih Hari</option>
                        <option value="senin" {{ $hari == 'senin' ? 'selected' : '' }}>Senin</option>
                        <option value="selasa" {{ $hari == 'selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="rabu" {{ $hari == 'rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="kamis" {{ $hari == 'kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="jumat" {{ $hari == 'jumat' ? 'selected' : '' }}>Jumat</option>
                    </select>
                    <input type="time" name="waktu" id="waktu" class="form-control" style="min-width: 120px;" onchange="this.form.submit()">
                </form>
            </div>
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
                           @if(isset($jadwal->dosen) && isset($jadwal->dosen->user) && !empty($jadwal->dosen->user->foto))
                                <img src="{{ asset($jadwal->dosen->user->foto) }}" alt="foto dosen" class="img-thumbnail w-25 d-block mx-auto">
                            @else
                                <img src="{{ asset('foto-pengguna/noset.jpg') }}" alt="foto dosen" class="img-thumbnail w-25 d-block mx-auto">
                            @endif
                            <p class="text-center mt-1 text-muted">Dosen : {{ $jadwal->dosen->nama_dosen }}</p>
                            <button class="btn btn-{{ $jadwal->status_ruang == 'digunakan' ? 'success' : 'warning' }} btn-sm text-uppercase">
                                {{ $jadwal->status_ruang }}
                            </button>
                        </div>
                    </div>
                @empty
                <div class="d-flex justify-content-center flex-column align-items-center w-100">
                    <h5 class="text-center text-muted">Tidak ada jadwal laboratorium yang tersedia</h5>
                    <p class="text-center text-muted">Silakan cek kembali nanti.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    {{--Laboratorium--}}
@endsection
