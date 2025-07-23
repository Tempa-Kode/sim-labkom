<!doctype html>
<html lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIM-LABKOM</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />
    <script src="https://kit.fontawesome.com/8d46f8acca.js" crossorigin="anonymous"></script>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>
<body>

{{--Menu--}}
<nav class="navbar navbar-expand-lg bg-light m-4 rounded-3 sticky-top zindex-1">
    <div class="container">
        <a class="navbar-brand" href="#">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/logo-fikom.png') }}" alt="logo fikom" width="50" height="50" class="d-inline-block align-text-top">
                <h2 class="ms-4 mb-0 fw-bold">FIKOM UST</h2>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold" aria-current="page" href="#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" aria-current="page" href="#tentang">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" aria-current="page" href="#laboratorium">Laboratorium</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" aria-current="page" href="#kontak">Kontak</a>
                </li>
                @if(!Auth::check())
                <li class="nav-item">
                    <a class="btn btn-primary nav-link fw-bold text-white px-3" aria-current="page" href="{{ route('login') }}">Login</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="btn btn-primary nav-link fw-bold text-white px-3" aria-current="page" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
{{--Menu--}}

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

{{--Tentang--}}
<div id="tentang" class="vh-75 d-flex align-items-center justify-content-center bg-white">
    <div class="container col-xxl-8 px-4 py-5">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-10 col-sm-8 col-lg-6">
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

{{--Laboratorium--}}
<div id="laboratorium" class="vh-100 d-flex align-items-center justify-content-center bg-white">
    <div class="container">
        <h1 class="text-center">Ruang</h1>
        <h1 class="text-center">Laboratorium Komputer</h1>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4 mt-3">
            @forelse($dataJadwal as $jadwal)
                <div class="col">
                    <div class="card h-100">
                        <h5 class="card-header text-center fw-bold bg-secondary text-white">{{ $jadwal->ruangLaboratorium->nama_ruang }}</h5>
                        <div class="card-body text-center mt-3">
                            <p class="card-text text-capitalize">{{ $jadwal->hari }}</p>
                            <p class="card-text">Mulai {{ $jadwal->waktu_mulai }}</p>
                            <p class="card-text">Selai {{ $jadwal->waktu_selesai }}</p>
                        </div>
                        <p class="text-center mt-1 text-muted">Dosen : {{ $jadwal->dosen->nama_dosen }}</p>
                        <button class="btn btn-{{ $jadwal->status_ruang == 'digunakan' ? 'success' : 'warning' }} btn-sm text-uppercase">
                            {{ $jadwal->status_ruang }}
                        </button>
                    </div>
                </div>
            @empty
                <h5 class="text-center text-muted">Tidak ada jadwal laboratorium yang tersedia</h5>
                <p class="text-center text-muted">Silakan cek kembali nanti.</p>
            @endforelse
        </div>
    </div>
</div>
{{--Laboratorium--}}

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
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1008.5094214863091!2d98.62111849002339!3d3.544459610533088!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30312fce908813eb%3A0x2caf18684ec7e6c1!2sFIKOM%20UNIKA!5e1!3m2!1sen!2sid!4v1753292588523!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
{{--Kontak--}}

{{--Footer--}}
<div class="container">
    <footer class="py-3 my-4 border-top text-center">
        <div class="text-center">
            <p class="mb-3 mb-md-0 text-muted text-center">© 2025 - SIM-LABKOM Fakultas Ilmu Komputer Universitas Katolik Santo Thomas</p>
        </div>
    </footer>
</div>
{{--Footer--}}

<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('section, div[id]');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link[href^="#"]');

    function onScroll() {
        let scrollPos = window.scrollY || document.documentElement.scrollTop;
        let offset = 120;
        let found = false;
        sections.forEach(section => {
            if (section.id) {
                const secTop = section.offsetTop - offset;
                const secBottom = secTop + section.offsetHeight;
                if (scrollPos >= secTop && scrollPos < secBottom && !found) {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + section.id) {
                            link.classList.add('active');
                        }
                    });
                    found = true;
                }
            }
        });

        if (!found) {
            navLinks.forEach(link => link.classList.remove('active'));
        }
    }

    window.addEventListener('scroll', onScroll);
    onScroll();
});
</script>
</body>
</html>
