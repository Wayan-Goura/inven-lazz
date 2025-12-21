@extends('layouts.app')
@section('content')

<div class="container-fluid">
    {{-- Tampilkan error jika ada masalah pada transaction --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    {{-- Tampilkan error validasi jika ada --}}
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
                        
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700" for="data_barang_id">
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
                                            {{ old('data_barang_id') == $barang->id ? 'selected' : '' }}
                                        >
                                            {{ $barang->k_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->jml_stok }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('data_barang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

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
                                    placeholder="Jumlah barang"
                                    required
                                >
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
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
                                    placeholder="TRX-001"
                                    required
                                >
                                @error('kode_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
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
                                    <option value="">-- Pilih Tipe Transaksi --</option>
                                    <option value="masuk" {{ old('tipe_transaksi') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                                    <option value="keluar" {{ old('tipe_transaksi') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                </select>
                                @error('tipe_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
                                    <option value="Jl. Melati No.10, Batubulan" {{ old('lokasi') == 'Jl. Melati No.10, Batubulan' ? 'selected' : '' }}>Jl. Melati No.10, Batubulan</option>
                                    <option value="Jl. Kenanga No.5, Klungkung" {{ old('lokasi') == 'Jl. Kenanga No.5, Klungkung' ? 'selected' : '' }}>Jl. Kenanga No.5, Klungkung</option>
                                    <option value="Jl. Anggrek No.7, Ubud" {{ old('lokasi') == 'Jl. Anggrek No.7, Ubud' ? 'selected' : '' }}>Jl. Anggrek No.7, Ubud</option>
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
@endsection