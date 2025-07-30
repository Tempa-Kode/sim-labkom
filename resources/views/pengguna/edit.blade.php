@extends('layout.app')

@section('judul', 'Ubah Akun')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Ubah Akun</h4>
        @include('komponen.alert')
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ubah Data {{ $user->nama }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengguna.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $user->nama) }}"
                            />
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}"
                            />
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="hak_akses" class="form-label">Hak Akses</label>
                            <select id="hak_akses" class="form-select" name="hak_akses">
                                <option value="" hidden>Pilih Hak Akses</option>
                                <option value="admin" @if (old('hak_akses', $user->hak_akses) == 'admin') selected @endif>Admin</option>
                                <option value="dosen" @if (old('hak_akses', $user->hak_akses) == 'dosen') selected @endif>Dosen</option>
                                <option value="aslab" @if (old('hak_akses', $user->hak_akses) == 'aslab') selected @endif>Aslab</option>
                            </select>
                            @error('hak_akses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    value="{{ old('password') }}"
                                    class="form-control @error('password') is-invalid @enderror"
                                />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col mb-0">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    value="{{ old('password_confirmation') }}"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                />
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Edit</button>
                        <a href="{{ route('pengguna.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
