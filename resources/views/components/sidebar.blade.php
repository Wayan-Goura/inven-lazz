<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-bold">STOK BARANG</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('barang.index') }}" 
                       class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Data Barang</p>
                    </a>
                </li>

                <li class="nav-item has-treeview 
                    {{ request()->routeIs('barang.masuk*') || request()->routeIs('barang.keluar*') || request()->routeIs('barang.return*') ? 'menu-open' : '' }}">

                    <a href="#" class="nav-link
                        {{ request()->routeIs('barang.masuk*') || request()->routeIs('barang.keluar*') || request()->routeIs('barang.return*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Kelola Barang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('barang.masuk.index') }}" 
                            class="nav-link {{ request()->routeIs('barang.masuk*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Barang Masuk</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('barang.keluar.index') }}" 
                            class="nav-link {{ request()->routeIs('barang.keluar*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Barang Keluar</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('barang.return.index') }}" 
                            class="nav-link {{ request()->routeIs('barang.return*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Barang Return</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('barang.catagory.index') }}"
                            class="nav-link {{ request()->routeIs('barang.catagory*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item">
                    <a href="{{ route('toko') }}" 
                       class="nav-link {{ request()->routeIs('toko') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Kelola Toko</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('profile') }}" 
                       class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p class="text-danger">Logout</p>
                    </a>
                </li>

            </ul>
        </nav>

    </div>
</aside>
