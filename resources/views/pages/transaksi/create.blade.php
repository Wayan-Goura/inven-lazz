@extends('layouts.app')
@section('content')

<div class="container-fluid">

    <!-- WRAPPER ISI -->
    <div class="mx-auto" style="max-width: 900px;">

        <div class="card shadow">

            <!-- Header (FIXED) -->
            <div class="card-header py-3 d-flex align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-box-open mr-2"></i> Input Barang
                </h6>
            </div>

            <form>

                <!-- BODY (SCROLLABLE) -->
                <div class="card-body"
                     style="
                        max-height: calc(100vh - 260px);
                        overflow-y: auto;
                     ">

                    <div class="row">

                        <!-- Kode Barang -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Kode Barang <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="Masukkan kode barang">
                            </div>
                        </div>

                        <!-- Nama Barang -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Nama Barang <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="Masukkan nama barang">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Kategori
                                </label>
                                <select class="form-control">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option>Helm</option>
                                    <option>Kaca</option>
                                    <option>Tali</option>
                                </select>
                            </div>
                        </div>

                        <!-- Merk -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Merk
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="Merk barang">
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Tanggal
                                </label>
                                <input type="date" class="form-control">
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Lokasi
                                </label>
                                <select class="form-control">
                                    <option value="">-- Pilih Lokasi --</option>
                                    <option>Ubud</option>
                                    <option>Batubulan</option>
                                    <option>Klungkung</option>
                                </select>
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Jumlah
                                </label>
                                <input type="number" class="form-control"
                                       placeholder="Jumlah barang">
                            </div>
                        </div>

                        <!-- Jenis Barang -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Jenis Barang
                                </label>
                                <select class="form-control">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option>Barang Masuk</option>
                                    <option>Barang Keluar</option>
                                    <option>Barang Return</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer (FIXED) -->
                <div class="card-footer bg-white d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection
