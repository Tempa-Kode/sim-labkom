@extends('layout.app')

@section('judul', 'Data Absensi Aslab')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Absensi </h4>
        @include('komponen.alert')
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-center align-items-center">
                <h5 class="mb-0 text-uppercase text-center">ABSENSI</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('absensi.absensi') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="id_ruang_lab">Nama Aslab</label>
                        <div class="col-sm-10">
                            <input type="text" name="id_pengguna" id="id_pengguna" value="{{ Auth::user()->id }}" hidden>
                            <input type="text" name="nama" id="nama" class="form-control" value="{{ Auth::user()->nama }}" readonly>
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
                        <label class="col-sm-2 col-form-label" for="tanggal">Tanggal</label>
                        <div class="col-sm-10">
                            <input class="form-control @error('tanggal') is-invalid @enderror" type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"/>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                        <div class="col-sm-10">
                            <select id="keterangan" name="keterangan" class="form-select @error('keterangan') is-invalid @enderror">
                                <option value="" hidden>Pilih</option>
                                <option value="Hadir" @if(old('keterangan') == 'Hadir') selected @endif>Hadir</option>
                                <option value="Izin" @if(old('keterangan') == 'Izin') selected @endif>Izin</option>
                                <option value="Sakit" @if(old('keterangan') == 'Sakit') selected @endif>Sakit</option>
                                <option value="Absen" @if(old('keterangan') == 'Absen') selected @endif>Absen</option>
                            </select>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary w-100">Absen</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <h5 class="card-header">Riwayat Absensi</h5>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Hari</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach ($data as $absensi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $absensi->hari }}</td>
                            <td>{{ $absensi->tanggal }}</td>
                            <td>{{ $absensi->keterangan ?? "-" }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#tanggal').on('change', function() {
            $('#filter-tanggal').submit();
        });
    </script>
@endpush
