@extends("layout.app")

@section("judul", "Tambah Notifikasi")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home / Notifikasi /</span> Tambah</h4>
        @include("komponen.alert")
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <h5 class="mb-0 text-uppercase">Form Tambah Notifikasi</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("notifikasi.simpan") }}" method="POST">
                            @csrf
                            @method("POST")
                            <div class="mb-3">
                                <label for="jadwal_id" class="form-label">Pilih Jadwal</label>
                                <select name="jadwal_id" id="jadwal_id" class="form-select">
                                    @foreach ($jadwal as $j)
                                        <option value="{{ $j->id }}">{{ $j->ruangLaboratorium->nama_ruang ?? "-" }} -
                                            {{ $j->hari }} {{ $j->waktu_mulai }}-{{ $j->waktu_selesai }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="judul" name="judul"
                                    value="{{ old("judul") }}">
                            </div>
                            <div class="mb-3">
                                <label for="pesan" class="form-label">Pesan</label>
                                <input type="text" class="form-control" id="pesan" name="pesan"
                                    value="{{ old("pesan") }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route("notifikasi.index") }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
