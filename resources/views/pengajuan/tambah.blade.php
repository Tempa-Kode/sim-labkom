@extends("layout.app")

@section("judul", "Pengajuan")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home / </span> Pengajuan </h4>
        @include("komponen.alert")
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <h5 class="mb-0 text-uppercase">Pengajuan Penggunaan Ruang Lab</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengajuan.simpan') }}" method="POST">
                            @csrf
                            @method("POST")
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_ruang">Ruang Lab</label>
                                <div class="col-sm-10">
                                    <select id="id_ruang" name="id_ruang"
                                        class="form-select @error("id_ruang") is-invalid @enderror">
                                        <option value="" hidden>Pilih Ruang Lab</option>
                                        @foreach ($ruangLab as $ruang)
                                            <option value="{{ $ruang->id }}"
                                                @if (old("id_ruang") == $ruang->id) selected @endif>{{ $ruang->nama_ruang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="tanggal_pengajuan">Tanggal Pengajuan</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error("tanggal_pengajuan") is-invalid @enderror"
                                        type="date" id="tanggal_pengajuan" name="tanggal_pengajuan"
                                        value="{{ date("Y-m-d") }}" readonly />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="tanggal_pemakaian">Tanggal Pemakaian</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error("tanggal_pemakaian") is-invalid @enderror"
                                        type="date" id="tanggal_pemakaian" name="tanggal_pemakaian"
                                        value="{{ old("tanggal_pemakaian") }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jam_mulai">Waktu Mulai</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error("jam_mulai") is-invalid @enderror" type="time"
                                        id="jam_mulai" name="jam_mulai" value="{{ old("jam_mulai") }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jam_selesai">Waktu Selesai</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error("jam_selesai") is-invalid @enderror" type="time"
                                        id="jam_selesai" name="jam_selesai"
                                        value="{{ old("jam_selesai") }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_dosen">Nama Dosen</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text"
                                        id="id_dosen" name="id_dosen" value="{{ Auth::user()->dosen->id }}" hidden />
                                    <input class="form-control @error("dosen") is-invalid @enderror" type="text"
                                        id="dosen" name="dosen" value="{{ Auth::user()->nama }}" readonly />
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Di Ajukan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
