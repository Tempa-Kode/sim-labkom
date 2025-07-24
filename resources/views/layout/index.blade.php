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
    <title>@yield('judul', 'SIM-LABKOM')</title>
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
                    <a class="nav-link fw-bold {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ Route::currentRouteName() == 'tentang' ? 'active' : '' }}" aria-current="page" href="{{ route('tentang') }}">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ Route::currentRouteName() == 'laboratorium' ? 'active' : '' }}" aria-current="page" href="{{ route('laboratorium') }}">Laboratorium</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ Route::currentRouteName() == 'kontak' ? 'active' : '' }}" aria-current="page" href="{{ route('kontak') }}">Kontak</a>
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

@yield('konten')

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
</body>
</html>
