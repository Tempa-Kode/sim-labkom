@extends('layout.index')

@section('judul', 'Tentang - SIM-LABKOM')

@section('konten')
    {{--Tentang--}}
    <div id="tentang" class="vh-75 d-flex align-items-center justify-content-center bg-white">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center justify-content-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6 d-flex justify-content-center">
                    <img src="{{ asset('assets/img/logo-fikom.png') }}" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="400" height="400" loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3 text-center">Tentang</h1>
                    <h1 class="display-5 fw-bold lh-1 mb-3 text-center">SIM-LABKOM</h1>
                    <p class="lead" style="text-align: justify">Sistem Informasi Manajemen Laboratorium Komputer (SIM-LABKOM) adalah sebuah sistem berbasis teknologi yang dirancang untuk mengelola dan mengoptimalkan operasional laboratorium komputer. Sistem ini mencakup manajemen pemeliharaan komputer, manajemen laporan kerusakan, manajemen atau pemantauan petugas laboratorium, serta pengelolaan perawatan perangkat dan penanggung jawab. Dengan adanya SIM-LABKOM, efisiensi pengelolaan laboratorium dapat meningkat, meminimalkan kesalahan administrasi, serta memastikan penggunaan sumber daya secara optimal.</p>
                </div>
            </div>
        </div>
    </div>
    {{--Tentang--}}
@endsection
