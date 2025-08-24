@extends("layout.index")

@section("judul", "Laboratorium - SIM-LABKOM")

@section("konten")
    {{-- Laboratorium --}}
    <div id="laboratorium" class="vh-lg-100 d-flex align-items-center justify-content-center bg-white">
        <div class="container">
            <h1 class="text-center">Ruang</h1>
            <h1 class="text-center">Laboratorium Komputer</h1>
            <h3 class="text-center">
                {{ $tanggalHariIni }}
            </h3>
            <div class="d-flex justify-content-center mt-4">
                <form action="" class="d-flex align-items-center gap-3 flex-nowrap flex-wrap">
                    <select name="hari" id="hari" class="form-select" style="min-width: 150px;"
                        onchange="this.form.submit()">
                        <option value="" hidden>Pilih Hari</option>
                        <option value="senin" {{ $hari == "senin" ? "selected" : "" }}>Senin</option>
                        <option value="selasa" {{ $hari == "selasa" ? "selected" : "" }}>Selasa</option>
                        <option value="rabu" {{ $hari == "rabu" ? "selected" : "" }}>Rabu</option>
                        <option value="kamis" {{ $hari == "kamis" ? "selected" : "" }}>Kamis</option>
                        <option value="jumat" {{ $hari == "jumat" ? "selected" : "" }}>Jumat</option>
                    </select>
                    <input type="time" name="waktu" id="waktu" class="form-control" style="min-width: 120px;"
                        onchange="this.form.submit()">
                </form>
            </div>

            @php
                use App\Helpers\JamHelper;
                // Pisahkan jadwal berdasarkan status
                $jadwalDigunakan = $dataJadwal->where("status_ruang", "digunakan");
                $jadwalKosong = $dataJadwal->where("status_ruang", "kosong");
            @endphp

            {{-- Section 1: Jadwal Penggunaan Lab --}}
            @if ($jadwalDigunakan->count() > 0)
                <div class="mt-5">
                    <h2 class="text-center mb-4" style="color: #28a745;">📚 Jadwal Penggunaan Lab</h2>
                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                        @foreach ($jadwalDigunakan as $jadwal)
                            <div class="col">
                                <div class="card h-100 border-success">
                                    <h5 class="card-header text-center fw-bold bg-success text-white">
                                        {{ $jadwal->ruangLaboratorium->nama_ruang }}</h5>
                                    <div class="card-body text-center mt-3">
                                        <p class="card-text text-capitalize">{{ $jadwal->hari }}</p>
                                        <p class="card-text">
                                            <strong>{{ JamHelper::formatJam($jadwal->waktu_mulai, $jadwal->waktu_selesai) }}</strong>
                                        </p>
                                        <p class="card-text text-muted small">{{ $jadwal->waktu_mulai }} -
                                            {{ $jadwal->waktu_selesai }}</p>
                                    </div>
                                    @if (isset($jadwal->dosen) && isset($jadwal->dosen->user) && !empty($jadwal->dosen->user->foto))
                                        <img src="{{ asset($jadwal->dosen->user->foto) }}" alt="foto dosen"
                                            class="img-thumbnail w-25 d-block mx-auto">
                                    @else
                                        <img src="{{ asset("foto-pengguna/noset.jpg") }}" alt="foto dosen"
                                            class="img-thumbnail w-25 d-block mx-auto">
                                    @endif
                                    <p class="text-center mt-1 text-muted">Dosen: {{ $jadwal->dosen->nama_dosen }}</p>

                                    {{-- Informasi petugas aslab yang membuat jadwal --}}
                                    @if ($jadwal->pembuatJadwal)
                                        <p class="text-center text-muted small">
                                            <i class="fas fa-user-cog"></i> Dibuat oleh: {{ $jadwal->pembuatJadwal->nama }}
                                        </p>
                                    @endif

                                    <div class="text-center mb-3">
                                        <span
                                            class="badge bg-success text-uppercase px-3 py-2">{{ $jadwal->status_ruang }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Section 2: Jadwal Ruang Kosong --}}
            @if ($jadwalKosong->count() > 0)
                <div class="mt-5">
                    <h2 class="text-center mb-4" style="color: #ffc107;">🏢 Jadwal Ruang Kosong</h2>
                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                        @foreach ($jadwalKosong as $jadwal)
                            <div class="col">
                                <div class="card h-100 border-warning">
                                    <h5 class="card-header text-center fw-bold bg-warning text-dark">
                                        {{ $jadwal->ruangLaboratorium->nama_ruang }}</h5>
                                    <div class="card-body text-center mt-3">
                                        <p class="card-text text-capitalize">{{ $jadwal->hari }}</p>
                                        <p class="card-text">
                                            <strong>{{ JamHelper::formatJam($jadwal->waktu_mulai, $jadwal->waktu_selesai) }}</strong>
                                        </p>
                                        <p class="card-text text-muted small">{{ $jadwal->waktu_mulai }} -
                                            {{ $jadwal->waktu_selesai }}</p>

                                        {{-- Alasan ruang kosong --}}
                                        @if ($jadwal->alasan_kosong)
                                            <div class="alert alert-warning py-2 mt-2">
                                                <i class="fas fa-info-circle"></i> {{ $jadwal->alasan_kosong }}
                                            </div>
                                        @else
                                            <div class="alert alert-info py-2 mt-2">
                                                <i class="fas fa-info-circle"></i> Ruang tersedia untuk digunakan
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Informasi dosen dan foto default untuk ruang kosong --}}
                                    <img src="{{ asset("foto-pengguna/noset.jpg") }}" alt="ruang kosong"
                                        class="img-thumbnail w-25 d-block mx-auto" style="opacity: 0.5;">
                                    <p class="text-center mt-1 text-muted">
                                        {{ $jadwal->dosen->nama_dosen ?? "Tidak ada dosen" }}</p>

                                    {{-- Informasi petugas aslab yang membuat jadwal --}}
                                    @if ($jadwal->pembuatJadwal)
                                        <p class="text-center text-muted small">
                                            <i class="fas fa-user-cog"></i> Dibuat oleh: {{ $jadwal->pembuatJadwal->nama }}
                                        </p>
                                    @endif

                                    <div class="text-center mb-3">
                                        <span
                                            class="badge bg-warning text-dark text-uppercase px-3 py-2">{{ $jadwal->status_ruang }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Jika tidak ada jadwal sama sekali --}}
            @if ($dataJadwal->count() == 0)
                <div class="d-flex justify-content-center flex-column align-items-center w-100 mt-5">
                    <h5 class="text-center text-muted">Tidak ada jadwal laboratorium yang tersedia</h5>
                    <p class="text-center text-muted">Silakan cek kembali nanti.</p>
                </div>
            @endif
        </div>
    </div>
    {{-- Laboratorium --}}
@endsection
