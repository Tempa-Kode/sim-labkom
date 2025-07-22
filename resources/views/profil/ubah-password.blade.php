@extends('layout.app')

@section('judul', 'Ubah Password')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
@endpush

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Halaman / Profil /</span> Ubah Password</h4>
        @include('komponen.alert')
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Ubah Password</h5>
                    <div class="card-body">
                        <form id="formAccountSettings" action="{{ route('profil.updatePassword') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Password Lama</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input
                                            type="password"
                                            id="password_lama"
                                            class="form-control @error('password_lama') is-invalid @enderror"
                                            name="password_lama"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password_lama"
                                        />
                                        @error('password_lama')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Password Baru</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input
                                            type="password"
                                            id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password"
                                        />
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Konfirmasi Password Baru</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input
                                            type="password"
                                            id="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password_confirmation"
                                        />
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                    Simpan Perubahan
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
                                    <i class="fa-solid fa-ban me-2"></i>
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
