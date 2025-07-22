@extends("layout.app")

@section("judul", "Dashboard")

@section("konten")
    <div class="container-xxl flex-grow-1 container-p-y d-flex align-items-center justify-content-center">
        <div class="row text-center d-flex flex-column align-items-center justify-content-center">
            <img src="{{ asset('assets/img/logo-fikom.png') }}" alt="logo fikom" class="img-fluid mb-5" style="max-width: 250px; height: auto;">
            <h2 class="text-uppercase">Selamat Datang {{ Auth::user()->nama }}!</h2>
            <h5>Sistem Informasi Manajemen Laboratorium Komputer</h5>
            <h5>Fakultas Ilmu Komputer</h5>
        </div>
    </div>
@endsection
