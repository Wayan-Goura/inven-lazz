<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Inventory Helmet</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .main-sidebar {
            background: #1f2d3d !important;
            padding: 0 !important;
        }

        .brand-link {
            background: #ffb100 !important;
            color: #000 !important;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <span class="ml-3 font-weight-bold text-lg">
            Inventory Helmet
        </span>
    </nav>

    @include('components.sidebar')

    <div class="content-wrapper p-4">
        @yield('content')
    </div>

</div>

</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/adminlte.min.js') }}"></script>

<script>
    
    $(document).ready(function() {
        $('[data-widget="treeview"]').Treeview();
    });
</script>


</body>
</html>