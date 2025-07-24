@extends('layout.index')

@section('konten')
    {{--Beranda--}}
    <div id="beranda" class="vh-100 d-flex align-items-center justify-content-center" style="margin-top: -129px; background-image: url('{{ asset('assets/img/backgrounds/bg-hero.jpg') }}'); background-size: cover; background-position: center;">
        <div class="px-4 py-5 my-5 text-center text-white">
            <p class="lead mb-4">👋 Selamat Datang Di</p>
            <div class="col-lg-12 mx-auto">
                <h1 class="display-5 fw-bold text-white fs-1">Sistem Informasi</h1>
                <h1 class="display-5 fw-bold text-white fs-2">Manajemen Laboratorium Komputer</h1>
            </div>
        </div>
    </div>
    {{--Beranda--}}
@endsection
