@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Profil Saya</h1>

    <!-- CARD PROFIL -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">

                <!-- FOTO -->
                <div class="col-md-3 text-center">
                    <img src="{{ asset('images/Lazz.jpeg') }}"
                         class="img-fluid rounded-circle mb-3"
                         style="max-width:150px;">
                </div>

                <!-- INFO -->
                <div class="col-md-9">
                    <h4 class="font-weight-bold">Gudang Lazz Helmet</h4>
                    <p class="mb-1"><strong>Email:</strong> example@email.com</p>
                    <p class="mb-1"><strong>Telepon:</strong> +62 812-3456-7890</p>
                    <p class="mb-3"><strong>Alamat:</strong> Jl. Raya Ubud No.12, Bali</p>

                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-user-edit"></i> Edit Profil
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- INFO TAMBAHAN -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Info Tambahan</h6>
        </div>
        <div class="card-body">
            <p class="text-muted mb-0">Belum ada info tambahan.</p>
        </div>
    </div>

</div>
@endsection
