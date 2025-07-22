@extends('layout.app')

@section('judul', 'Tambah Ruang Lab')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home / Jadwal Ruang Lab /</span> Tambah</h4>
        @include('komponen.alert')
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <h5 class="mb-0 text-uppercase">Form Jadwal Penggunaan Ruang Laboratorium</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('jadwalLab.simpan') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_ruang_lab">Ruang Lab</label>
                                <div class="col-sm-10">
                                    <select id="id_ruang_lab" name="id_ruang_lab" class="form-select @error('id_ruang_lab') is-invalid @enderror">
                                        <option value="" hidden>Pilih Ruang Lab</option>
                                        @foreach ($ruangLab as $ruang)
                                            <option value="{{ $ruang->id }}" @if (old('id_ruang_lab') == $ruang->id) selected @endif>{{ $ruang->nama_ruang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="hari">Hari</label>
                                <div class="col-sm-10">
                                    <select id="hari" name="hari" class="form-select @error('hari') is-invalid @enderror">
                                        <option value="" hidden>Pilih Hari</option>
                                        <option value="senin" @if (old('hari') == 'senin') selected @endif>Senin</option>
                                        <option value="selasa" @if (old('hari') == 'selasa') selected @endif>Selasa</option>
                                        <option value="rabu" @if (old('hari') == 'rabu') selected @endif>Rabu</option>
                                        <option value="kamis" @if (old('hari') == 'kamis') selected @endif>Kamis</option>
                                        <option value="jumat" @if (old('hari') == 'jumat') selected @endif>Jumat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="waktu_mulai">Waktu Mulai</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error('waktu_mulai') is-invalid @enderror" type="time" value="00:00" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="waktu_selesai">Waktu Selesai</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error('waktu_selesai') is-invalid @enderror" type="time" value="00:00" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_dosen">Nama Dosen</label>
                                <div class="col-sm-10">
                                    <select id="id_dosen" name="id_dosen" class="form-select @error('id_dosen') is-invalid @enderror">
                                        <option value="" hidden>Pilih Dosen</option>
                                        @foreach ($dosen as $dsn)
                                            <option value="{{ $dsn->id }}" @if (old('id_dosen') == $dsn->id) selected @endif>{{ $dsn->nama_dosen }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="status_ruang">Status</label>
                                <div class="col-sm-10">
                                    <select id="status_ruang" name="status_ruang" class="form-select @error('status_ruang') is-invalid @enderror">
                                        <option value="" hidden>Pilih Status</option>
                                        <option value="digunakan" @if(old('status_ruang') == 'digunakan') selected @endif>Digunakan</option>
                                        <option value="kosong" @if(old('status_ruang') == 'kosong') selected @endif>Kosong</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
