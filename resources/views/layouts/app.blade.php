<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Helmet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- FIX SCROLL & LAYOUT -->
    <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }

        #wrapper {
            height: 100vh;
        }

        #content-wrapper {
            height: 100vh;
            overflow: hidden;
        }

        #content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .page-content-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: #f8f9fc;
        }
    </style>
</head>

<body id="page-top">

<div id="wrapper">

    {{-- SIDEBAR --}}
    @include('components.sidebar')

    <!-- CONTENT WRAPPER -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- MAIN CONTENT -->
        <div id="content">

            <!-- TOPBAR -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar shadow">

                <!-- TOGGLE DESKTOP -->
                <button id="sidebarToggleCustom"
                        class="btn btn-link rounded-circle mr-3 d-none d-md-inline">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- TOGGLE MOBILE (HAMBURGER) -->
                <button id="sidebarToggleTop"
                        class="btn btn-link rounded-circle mr-3 d-md-none">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- TITLE -->
                <h5 class="mb-0 font-weight-bold text-gray-700">
                    Inventory Helmet
                </h5>

                <!-- TOPBAR RIGHT -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item d-flex align-items-center">
                        <span class="mr-3 text-gray-600 small font-weight-bold">
                            Welcome, {{ auth()->user()->name }}
                        </span>
                    </li>
                </ul>

            </nav>

            <!-- PAGE CONTENT -->
            <div class="page-content-scroll">
                @yield('content')
            </div>

        </div>

        <!-- FOOTER -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>© 2025 Inventory Helmet</span>
                </div>
            </div>
        </footer>

    </div>

</div>

<!-- JS -->
<script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>

<!-- CUSTOM SIDEBAR TOGGLE -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const sidebar = document.querySelector('.sidebar');
        const desktopToggle = document.getElementById('sidebarToggleCustom');

        // LOAD STATUS (DESKTOP)
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            body.classList.add('sidebar-toggled');
            sidebar.classList.add('toggled');
        }

        // DESKTOP TOGGLE (ICON ONLY)
        desktopToggle.addEventListener('click', function () {
            body.classList.toggle('sidebar-toggled');
            sidebar.classList.toggle('toggled');

            const isCollapsed = sidebar.classList.contains('toggled');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
        });
    });
</script>

</body>
</html>
