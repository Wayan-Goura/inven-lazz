@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-gray-800">Profil Saya</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="mt-3 mb-4">
                        <img src="{{ asset('images/Lazz.jpeg') }}"
                             class="rounded-circle img-fluid shadow-sm" 
                             style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f8f9fc;">
                    </div>
                    <h4 class="font-weight-bold mb-0">Gudang Lazz Helmet</h4>
                    <p class="text-muted mb-4">Administrator Utama</p>
                    
                    <div class="d-flex justify-content-center mb-2">
                        <button type="button" class="btn btn-primary mr-2">
                            <i class="fas fa-edit fa-sm"></i> Edit Profil
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-key fa-sm"></i> Password
                        </button>
                    </div>
                </div>
                <hr class="mx-4 my-0">
                <div class="card-body">
                    <div class="row no-gutters align-items-center mb-3">
                        <div class="col-auto mr-3">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <div class="col">
                            <small class="text-muted d-block">Email</small>
                            <span class="font-weight-bold">lazzhelmet@email.com</span>
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto mr-3">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                        <div class="col">
                            <small class="text-muted d-block">Lokasi</small>
                            <span class="font-weight-bold">Ubud, Bali</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Produk Dikelola</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">All Product</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-helmet-safety fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Status Keamanan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Sangat Aman</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Lengkap</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="30%">Nama Lengkap</th>
                                <td>: Gudang Lazz Helmet</td>
                            </tr>
                            <tr>
                                <th>ID Pegawai</th>
                                <td>: <span class="badge badge-dark">ADM-001</span></td>
                            </tr>
                            <tr>
                                <th>Nomor Telepon</th>
                                <td>: +62 812-3456-7890</td>
                            </tr>
                            <tr>
                                <th>Alamat Lengkap</th>
                                <td>: Jl. Raya Ubud No.12, Gianyar, Bali - 80571</td>
                            </tr>
                            <tr>
                                <th>Bergabung Sejak</th>
                                <td>: 10 Januari 2024</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection