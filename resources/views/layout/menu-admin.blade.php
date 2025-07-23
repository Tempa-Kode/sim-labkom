<li class="menu-item {{ Route::currentRouteNamed('dashboard') ? 'active' : '' }}">
    <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="fa-solid fa-gauge-high me-4"></i>
        <div data-i18n="Analytics">Dashboard</div>
    </a>
</li>

<li class="menu-item {{ Route::currentRouteNamed('profil.index') ? 'active' : '' }}">
    <a href="{{ route('profil.index') }}" class="menu-link">
        <i class="fa-solid fa-address-card me-4"></i>
        <div data-i18n="Analytics">Profil</div>
    </a>
</li>

<li class="menu-item {{ Route::currentRouteNamed('pengguna.index') ? 'active' : '' }}">
    <a href="{{ route('pengguna.index') }}" class="menu-link">
        <i class="fa-solid fa-users me-4"></i>
        <div data-i18n="Analytics">Daftar Akun</div>
    </a>
</li>

<li class="menu-item {{ Route::currentRouteNamed('absensi.index') ? 'active' : '' }}">
    <a href="{{ route('absensi.index') }}" class="menu-link">
        <i class="fa-solid fa-clipboard-user me-4"></i>
        <div data-i18n="Analytics">Data Absensi Aslab</div>
    </a>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Data Laboratorium</span>
</li>
<li class="menu-item {{ Route::currentRouteNamed('ruangLab.index') ? 'active' : '' }}">
    <a href="{{ route('ruangLab.index') }}" class="menu-link">
        <i class="fa-solid fa-door-open me-4"></i>
        <div data-i18n="Analytics">Data Ruang Lab</div>
    </a>
</li>
<li class="menu-item {{ Route::currentRouteNamed('inventaris.index') ? 'active' : '' }}">
    <a href="{{ route('inventaris.index') }}" class="menu-link">
        <i class="fa-solid fa-warehouse me-4"></i>
        <div data-i18n="Analytics">Data Inventaris Lab</div>
    </a>
</li>

<li class="menu-item {{ Route::currentRouteNamed('jadwalLab.index') ? 'active' : '' }}">
    <a href="{{ route('jadwalLab.index') }}" class="menu-link">
        <i class="fa-solid fa-calendar-days me-4"></i>
        <div data-i18n="Analytics">Data Jadwal Lab</div>
    </a>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Lainnya</span>
</li>
<li class="menu-item">
    <a href="#" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa-solid fa-power-off me-4"></i>
        <div data-i18n="Documentation">Logout</div>
    </a>
</li>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
