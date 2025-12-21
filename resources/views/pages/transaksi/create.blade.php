@extends('layouts.app')
@section('content')

<div class="container-fluid">

    {{-- ALERT ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning">
            Mohon periksa kembali input Anda.
        </div>
    @endif

    <div class="mx-auto" style="max-width: 900px;">
        <div class="card shadow">
            <div class="card-header py-3 d-flex align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-box-open mr-2"></i> Transaksi Barang
                </h6>
            </div>

            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf

                <div class="card-body" style="max-height: calc(100vh - 260px); overflow-y: auto;">
                    <div class="row">

                        {{-- PILIH BARANG --}}
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Pilih Barang <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="data_barang_id" 
                                    id="data_barang_id" 
                                    class="form-control @error('data_barang_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($barangs as $barang)
                                        <option 
                                            value="{{ $barang->id }}"
                                            data-kode="{{ $barang->k_barang }}"
                                            data-stok="{{ $barang->jml_stok }}"
                                            {{ old('data_barang_id') == $barang->id ? 'selected' : '' }}
                                        >
                                            {{ $barang->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('data_barang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- KODE & STOK BARANG --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Kode Barang
                                </label>
                                <input 
                                    type="text" 
                                    id="kode_barang"
                                    class="form-control"
                                    readonly
                                >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Stok Tersedia
                                </label>
                                <input 
                                    type="number" 
                                    id="stok_barang"
                                    class="form-control"
                                    readonly
                                >
                            </div>
                        </div>

                        {{-- JUMLAH --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Jumlah <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="jumlah" 
                                    min="1"
                                    class="form-control @error('jumlah') is-invalid @enderror"
                                    value="{{ old('jumlah') }}"
                                    required
                                >
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Tanggal Transaksi <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    name="tanggal_transaksi"
                                    class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                    value="{{ old('tanggal_transaksi') ?? date('Y-m-d') }}"
                                    required
                                >
                                @error('tanggal_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- KODE TRANSAKSI --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Kode Transaksi <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="kode_transaksi" 
                                    class="form-control @error('kode_transaksi') is-invalid @enderror"
                                    value="{{ old('kode_transaksi') }}"
                                    required
                                >
                                @error('kode_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- TIPE --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Tipe Transaksi <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="tipe_transaksi" 
                                    class="form-control @error('tipe_transaksi') is-invalid @enderror"
                                    required
                                >
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="masuk">Masuk</option>
                                    <option value="keluar">Keluar</option>
                                </select>
                                @error('tipe_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- LOKASI --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Lokasi <span class="text-danger">*</span>
                                </label>
                                <select 
                                    name="lokasi" 
                                    class="form-control @error('lokasi') is-invalid @enderror"
                                    required
                                >
                                    <option value="">-- Pilih Lokasi --</option>
                                    <option value="Jl. Melati No.10, Batubulan">Jl. Melati No.10, Batubulan</option>
                                    <option value="Jl. Kenanga No.5, Klungkung">Jl. Kenanga No.5, Klungkung</option>
                                    <option value="Jl. Anggrek No.7, Ubud">Jl. Anggrek No.7, Ubud</option>
                                </select>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer bg-white d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('data_barang_id');
    const kode = document.getElementById('kode_barang');
    const stok = document.getElementById('stok_barang');

    function update() {
        const opt = select.options[select.selectedIndex];
        if (!opt || !opt.value) {
            kode.value = '';
            stok.value = '';
            return;
        }
        kode.value = opt.dataset.kode;
        stok.value = opt.dataset.stok;
    }

    select.addEventListener('change', update);
    update();
});
</script>

@endsection
