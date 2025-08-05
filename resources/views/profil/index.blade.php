@extends('layout.app')

@section('judul', 'Profil Pengguna')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Halaman /</span> Profil</h4>
        @include('komponen.alert')
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Profil</h5>
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img
                                src="{{ Auth::user()->foto ?? asset('foto-pengguna/noset.jpg') }}"
                                alt="user-avatar"
                                class="d-block rounded"
                                height="100"
                                width="100"
                                name="foto"
                                id="uploadedAvatar"
                            />
                            <form action="{{ route('profil.updateFoto') }}" method="POST" enctype="multipart/form-data" id="uploadFotoForm">
                                @csrf
                                @method('PUT')
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload foto baru</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input
                                            type="file"
                                            id="upload"
                                            name="foto"
                                            class="account-file-input"
                                            hidden
                                            accept="image/png, image/jpeg"
                                        />
                                    </label>
                                    <p class="text-muted mb-0">file JPG, GIF atau PNG. Ukuran maks 2mb</p>
                                    @error('foto')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                        <form id="formAccountSettings" action="{{ route('profil.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3 col-12">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input
                                        class="form-control @error('nama') is-invalid @enderror"
                                        type="text"
                                        id="nama"
                                        name="nama"
                                        value="{{ Auth::user()->nama }}"
                                        autofocus
                                    />
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="username" class="form-label">Username</label>
                                    <input class="form-control @error('username') is-invalid @enderror" type="text" name="username" id="username" value="{{ Auth::user()->username }}" />
                                    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="hak_akses" class="form-label">Hak Akses</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        id="hak_akses"
                                        readonly
                                        value="{{ Auth::user()->hak_akses }}"
                                    />
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                    Simpan Perubahan
                                </button>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fa-solid fa-ban me-2"></i>
                                    Batal
                                </button>
                                <a href="{{ route('profil.ubahPassword') }}" class="btn btn-danger">
                                    <i class="fa-solid fa-key me-2"></i>
                                    Ganti Password
                                </a>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('upload').addEventListener('change', function() {
            document.getElementById('uploadFotoForm').submit();
        });
    </script>
@endpush
