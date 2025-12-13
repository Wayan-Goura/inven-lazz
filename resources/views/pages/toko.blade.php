@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Kelola Toko</h1>

    <!-- Tombol Tambah -->
    <div class="mb-3">
        <button class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Tambah Toko
        </button>
    </div>

    <!-- INFO CARD -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <h6 class="text-primary font-weight-bold text-uppercase">Total Toko</h6>
                    <div class="h4 font-weight-bold">3</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <h6 class="text-success font-weight-bold text-uppercase">Toko Aktif</h6>
                    <div class="h4 font-weight-bold">3</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <h6 class="text-danger font-weight-bold text-uppercase">Toko Nonaktif</h6>
                    <div class="h4 font-weight-bold">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Toko</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $tokos = [
                            ['nama' => 'Lazz 1', 'alamat' => 'Jl. Melati No.10, Batubulan', 'status' => 'aktif'],
                            ['nama' => 'Lazz 2', 'alamat' => 'Jl. Kenanga No.5, Klungkung', 'status' => 'aktif'],
                            ['nama' => 'Nephew', 'alamat' => 'Jl. Anggrek No.7, Ubud', 'status' => 'aktif'],
                        ];
                    @endphp

                    @foreach ($tokos as $index => $toko)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $toko['nama'] }}</td>
                            <td>{{ $toko['alamat'] }}</td>
                            <td>
                                <span class="badge badge-success">
                                    {{ ucfirst($toko['status']) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
