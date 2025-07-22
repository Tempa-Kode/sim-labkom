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

<li class="menu-item">
    <a href="index.html" class="menu-link">
        <i class="fa-solid fa-clipboard-user me-4"></i>
        <div data-i18n="Analytics">Absensi</div>
    </a>
</li>

<li class="menu-item">
    <a href="index.html" class="menu-link">
        <i class="fa-solid fa-warehouse me-4"></i>
        <div data-i18n="Analytics">Daftar Barang Lab</div>
    </a>
</li>

<li class="menu-item">
    <a href="index.html" class="menu-link">
        <i class="fa-solid fa-calendar-days me-4"></i>
        <div data-i18n="Analytics">Kelola Jadwal Lab</div>
    </a>
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
