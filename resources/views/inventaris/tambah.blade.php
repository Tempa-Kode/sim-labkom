@extends('layout.app')

@section('judul', 'Tambah Inventaris')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home / Inventaris /</span> Tambah</h4>
        @include('komponen.alert')
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <h5 class="mb-0 text-uppercase">Tambah Barang</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('inventaris.simpan') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="kode_barang">Kode Barang</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error('kode_barang') is-invalid @enderror" type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="nama_barang">Nama Barang</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error('nama_barang') is-invalid @enderror" type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="kondisi">Kondisi</label>
                                <div class="col-sm-10">
                                    <select id="kondisi" name="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
                                        <option value="" hidden>Pilih Kondisi</option>
                                        <option value="Baik" @if(old('kondisi') == 'Baik') selected @endif>Baik</option>
                                        <option value="rusak" @if(old('kondisi') == 'rusak') selected @endif>Rusak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jumlah">Jumlah</label>
                                <div class="col-sm-10">
                                    <input class="form-control @error('jumlah') is-invalid @enderror" type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_jenis">Jenis</label>
                                <div class="col-sm-10">
                                    <select id="id_jenis" name="id_jenis" class="form-select @error('id_jenis') is-invalid @enderror">
                                        <option value="" hidden>Pilih Jenis</option>
                                        @foreach($jenis as $item)
                                            <option value="{{ $item->id }}" @if(old('id_jenis') == $item->id) selected @endif>{{ $item->nama_jenis }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="id_ruang">Ruangan</label>
                                <div class="col-sm-10">
                                    <select id="id_ruang" name="id_ruang" class="form-select @error('id_ruang') is-invalid @enderror">
                                        <option value="" hidden>Pilih Ruang</option>
                                        @foreach($ruangLab as $item)
                                            <option value="{{ $item->id }}" @if(old('id_ruang') == $item->id) selected @endif>{{ $item->nama_ruang }}</option>
                                        @endforeach
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
