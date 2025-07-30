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

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Laboratorium</span>
</li>
<li class="menu-item {{ Route::currentRouteNamed('pengajuan.tambah') ? 'active' : '' }}">
    <a href="{{ route('pengajuan.tambah') }}" class="menu-link">
        <i class="fa-solid fa-paper-plane me-4"></i>
        <div data-i18n="Analytics">Pengajuan</div>
    </a>
</li>
<li class="menu-item {{ Route::currentRouteNamed('pengajuan.index') ? 'active' : '' }}">
    <a href="{{ route('pengajuan.index') }}" class="menu-link">
        <i class="fa-solid fa-clock-rotate-left me-4"></i>
        <div data-i18n="Analytics">Riwayat Pengajuan</div>
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
