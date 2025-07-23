@extends('layout.app')

@section('judul', 'Tambah Jenis')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home / Jenis Inventaris /</span> Tambah</h4>
        @include('komponen.alert')
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Jenis Inventaris</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('jenisInventaris.simpan') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="nama_jenis" class="form-label">Nama Jenis</label>
                            <input
                                type="text"
                                id="nama_jenis"
                                name="nama_jenis"
                                class="form-control @error('nama_jenis') is-invalid @enderror"
                                value="{{ old('nama_jenis') }}"
                            />
                            @error('nama_jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                        <a href="{{ route('jenisInventaris.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
