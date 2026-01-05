<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- BRAND + TOGGLE -->
    <a class="sidebar-brand d-flex align-items-center justify-content-between" href="#">
        <div class="d-flex align-items-center">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="sidebar-brand-text mx-3">STOK BARANG</div>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- DASHBOARD -->
    @if(auth()->user()->role === 'super_admin')
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @endif

    <!-- DATA BARANG -->
    <li class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('barang.index') }}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Data Barang</span>
        </a>
    </li>

    <!-- TRANSAKSI -->
    <li class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('transaksi.create') }}">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Transaksi Barang</span>
        </a>
    </li>

    <!-- KELOLA BARANG -->
    @php
        $openBarang =
            request()->routeIs('kel_barang.b_masuk.*') ||
            request()->routeIs('kel_barang.b_keluar.*') ||
            request()->routeIs('kel_barang.b_return.*') ||
            request()->routeIs('kel_barang.catagory.*');
    @endphp

    <li class="nav-item">
        <a class="nav-link {{ $openBarang ? '' : 'collapsed' }}"
           href="#"
           data-toggle="collapse"
           data-target="#collapseBarang"
           aria-expanded="{{ $openBarang ? 'true' : 'false' }}">
            <i class="fas fa-fw fa-cog"></i>
            <span>Kelola Barang</span>
        </a>

        <div id="collapseBarang"
             class="collapse {{ $openBarang ? 'show' : '' }}"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                <a class="collapse-item {{ request()->routeIs('kel_barang.b_masuk.*') ? 'active' : '' }}"
                   href="{{ route('kel_barang.b_masuk.index') }}">
                    Barang Masuk
                </a>

                <a class="collapse-item {{ request()->routeIs('kel_barang.b_keluar.*') ? 'active' : '' }}"
                   href="{{ route('kel_barang.b_keluar.index') }}">
                    Barang Keluar
                </a>

                <a class="collapse-item {{ request()->routeIs('kel_barang.b_return.*') ? 'active' : '' }}"
                   href="{{ route('kel_barang.b_return.index') }}">
                    Barang Return
                </a>
                @if(auth()->user()->role === 'super_admin')
                <a class="collapse-item {{ request()->routeIs('kel_barang.catagory.*') ? 'active' : '' }}"
                   href="{{ route('kel_barang.catagory.index') }}">
                    Kategori
                </a>
                @endif
            </div>
        </div>
    </li>

    <!-- persetujuan -->
    @php
    $totalPersetujuan = \App\Models\DataBarang::where('is_disetujui', true)->count() +
                       \App\Models\Transaksi::where('is_disetujui', true)->count() +
                       \App\Models\BarangReturn::where('is_disetujui', true)->count();
    @endphp

<li class="nav-item {{ request()->routeIs('persetujuan.index') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('persetujuan.index') }}">
        <i class="fas fa-fw fa-users-cog"></i>
        <span>Persetujuan</span>
        @if($totalPersetujuan > 0)
            <span class="badge badge-danger badge-counter" style="font-size: 16px; margin-left: 5px;">
                {{ $totalPersetujuan > 9 ? '9+' : $totalPersetujuan }}
            </span>
        @endif
    </a>
</li>
    

    <!-- KELOLA ADMIN -->
    @if(auth()->user()->role === 'super_admin');
    <li class="nav-item {{ request()->routeIs('kel_role.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kel_role.index') }}">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Kelola Admin</span>
        </a>
    </li>
    @endif

    <!-- PROFIL (SEMUA ROLE) -->
    <li class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('profile') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- LOGOUT -->
    <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST" class="px-3">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm w-100">
                <i class="fas fa-sign-out-alt mr-1"></i>
                Logout
            </button>
        </form>
    </li>

</ul>
