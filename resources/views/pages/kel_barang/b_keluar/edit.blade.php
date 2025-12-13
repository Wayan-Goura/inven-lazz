@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- JUDUL -->
    <h1 class="h3 mb-4 text-gray-800">Edit Barang Keluar</h1>

    <div class="card shadow">
        <div class="card-body">

            {{-- FORM EDIT --}}
            <form action="#" method="post">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- KODE BARANG -->
                    <div class="col-md-6 mb-3">
                        <label>Kode Barang *</label>
                        <input type="text" name="kode_barang"
                               class="form-control"
                               value="BRG001" required>
                    </div>

                    <!-- NAMA BARANG -->
                    <div class="col-md-6 mb-3">
                        <label>Nama Barang *</label>
                        <input type="text" name="nama_barang"
                               class="form-control"
                               value="Bolpoin Hitam" required>
                    </div>

                    <!-- MERK -->
                    <div class="col-md-6 mb-3">
                        <label>Merk *</label>
                        <input type="text" name="merk"
                               class="form-control"
                               value="Standard" required>
                    </div>

                    <!-- TANGGAL -->
                    <div class="col-md-6 mb-3">
                        <label>Tanggal *</label>
                        <input type="date" name="tanggal"
                               class="form-control"
                               value="2025-01-13" required>
                    </div>

                    <!-- LOKASI -->
                    <div class="col-md-6 mb-3">
                        <label>Lokasi *</label>
                        <select name="lokasi" class="form-control">
                            <option value="Ubud" selected>Ubud</option>
                            <option value="Batubulan">Batubulan</option>
                            <option value="Klungkung">Klungkung</option>
                        </select>
                    </div>

                    <!-- JUMLAH -->
                    <div class="col-md-6 mb-3">
                        <label>Jumlah *</label>
                        <input type="number" name="jumlah"
                               class="form-control"
                               value="20" required>
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="mt-3">
                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>

                    <a href="{{ route('barang.keluar.index') }}"
                       class="btn btn-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
