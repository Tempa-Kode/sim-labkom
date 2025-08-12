@extends("layout.app")

@section("judul", "Profil Pengguna")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold pt-3"><span class="text-muted fw-light">Home /</span> Daftar Akun</h4>
        @include("komponen.alert")
        <div class="card">
            <h5 class="card-header">Akun</h5>
            <div class="px-3 mb-3 d-flex justify-content-between">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPenggunaModal">
                    <i class="fa-solid fa-square-plus me-2"></i>Tambah
                </button>
                <a class="btn btn-secondary" href="{{ route("pengguna.exportPdfDosen") }}">
                    <i class="fa-solid fa-file-pdf me-2"></i>Pdf Akun Dosen
                </a>
            </div>
            <div class="table-responsive text-nowrap px-3">
                <table id="datatables" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Hak Akses</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $pengguna)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pengguna->nama }}</td>
                                <td>{{ $pengguna->username }}</td>
                                <td class="text-capitalize">{{ $pengguna->hak_akses }}</td>
                                <td>
                                    <a href="{{ route("pengguna.edit", $pengguna->id) }}"
                                        class="btn btn-success btn-sm me-2">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route("pengguna.hapus", $pengguna->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Pengguna --}}
    <div class="modal fade" id="tambahPenggunaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPenggunaModalTitle">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route("pengguna.tambah") }}" method="post">
                    @csrf
                    @method("POST")
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" id="nama" name="nama"
                                    class="form-control @error("nama") is-invalid @enderror" value="{{ old("nama") }}" />
                                @error("nama")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username"
                                    class="form-control @error("username") is-invalid @enderror"
                                    value="{{ old("username") }}" />
                                @error("username")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="hak_akses" class="form-label">Hak Akses</label>
                                <select id="hak_akses" class="form-select" name="hak_akses">
                                    <option value="" hidden>Pilih Hak Akses</option>
                                    <option value="admin">Admin</option>
                                    <option value="dosen">Dosen</option>
                                    <option value="aslab">Aslab</option>
                                </select>
                                @error("hak_akses")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" value="{{ old("password") }}"
                                    class="form-control @error("password") is-invalid @enderror" />
                                @error("password")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col mb-0">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    value="{{ old("password_confirmation") }}"
                                    class="form-control @error("password_confirmation") is-invalid @enderror" />
                                @error("password_confirmation")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Tambah Pengguna --}}
@endsection
