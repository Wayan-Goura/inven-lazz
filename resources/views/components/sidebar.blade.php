<!-- Sidebar SB Admin 2 -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cubes"></i>
        </div>
        <div class="sidebar-brand-text mx-3">STOK BARANG</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Data Barang -->
    <li class="nav-item {{ request()->routeIs('barang.index') && !request()->routeIs('barang.masuk*') && !request()->routeIs('barang.keluar*') && !request()->routeIs('barang.return*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('barang.index') }}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Data Barang</span>
        </a>
    </li>

    <!-- Transaksi Barang -->
    <li class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('transaksi.create') }}">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Transaksi Barang</span>
        </a>
    </li>

    <!-- Kelola Barang -->
    <li class="nav-item">
        @php
            $openBarang = request()->routeIs('barang.masuk*') || request()->routeIs('barang.keluar*') || request()->routeIs('barang.return*') || request()->routeIs('barang.catagory*');
        @endphp
        <a class="nav-link collapsed {{ $openBarang ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseBarang" aria-expanded="{{ $openBarang ? 'true' : 'false' }}" aria-controls="collapseBarang">
            <i class="fas fa-fw fa-cog"></i>
            <span>Kelola Barang</span>
        </a>
        <div id="collapseBarang" class="collapse {{ $openBarang ? 'show' : '' }}" aria-labelledby="headingBarang" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('barang.masuk*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Barang Masuk</a>
                <a class="collapse-item {{ request()->routeIs('barang.keluar*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Barang Keluar</a>
                <a class="collapse-item {{ request()->routeIs('barang.return*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Barang Return</a>
                <a class="collapse-item {{ request()->routeIs('barang.catagory*') ? 'active' : '' }}" href="{{ route('barang.catagory.index') }}">Kategori</a>
            </div>
        </div>
    </li>

    <!-- Kelola Toko -->
    <li class="nav-item {{ request()->routeIs('toko') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('toko') }}">
            <i class="fas fa-fw fa-store"></i>
            <span>Kelola Toko</span>
        </a>
    </li>

    <!-- Profil -->
    <li class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('profile') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil</span>
        </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link text-danger" href="{{ route('logout') }}">
            <i class="fas fa-fw fa-sign-out-alt text-danger"></i>
            <span class="text-danger">Logout</span>
        </a>
    </li>

</ul>
