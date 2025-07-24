@extends('layout.index')

@section('judul', 'Kontak - SIM-LABKOM')

@section('konten')
    {{--Kontak--}}
    <div id="kontak" class="vh-75 d-flex align-items-center justify-content-center bg-white">
        <div class="container">
            <div class="container col-xxl-8 px-4 py-5">
                <div class="row align-items-center g-5 py-5">
                    <div class="col-lg-6">
                        <h1 class="display-5 fw-bold lh-1 mb-3 text-center">Kontak</h1>
                        <div class="d-flex gap-3 justify-content-center">
                            <img src="{{ asset('assets/img/logo-unika.png') }}" width="60" alt="logo unika">
                            <img src="{{ asset('assets/img/logo-fikom.png') }}" width="60" alt="logo fikom">
                        </div>
                        <h5 class="text-center mt-3">Fakultas Ilmu Komputer</h5>
                        <h5 class="text-center mt-3">Universitas Katolik Santo Thomas Medan</h5>
                        <hr class="bg-primary">
                        <div class="row">
                            <div class="col-1"><i class="fa-solid fa-location-dot"></i></div>
                            <div class="col-11">
                                <p>Jl. Setia Budi No.479, Tj. Sari, Kec. Medan Selayang, Kota Medan, Sumatera Utara 20133</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1"><i class="fa-brands fa-facebook"></i></div>
                            <div class="col-11">
                                <p>Fikom Unika</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1"><i class="fa-brands fa-instagram"></i></div>
                            <div class="col-11">
                                <p>@fikom.unika</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-10 col-sm-8 col-lg-6">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1008.5094214863091!2d98.62111849002339!3d3.544459610533088!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30312fce908813eb%3A0x2caf18684ec7e6c1!2sFIKOM%20UNIKA!5e1!3m2!1sen!2sid!4v1753292588523!5m2!1sen!2sid" width="350" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--Kontak--}}
@endsection
