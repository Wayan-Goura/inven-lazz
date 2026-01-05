@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Barang Keluar</h1>
    </div>

    {{-- Tampilkan Error jika validasi gagal atau stok tidak cukup --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Perubahan Transaksi: {{ $transaksi->kode_transaksi }}</h6>
        </div>
        <div class="card-body">

            {{-- FORM EDIT --}}
            <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    @php 
                        // Mengambil detail transaksi pertama
                        $detail = $transaksi->detailTransaksis->first(); 
                    @endphp

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Kode Transaksi</label>
                        <input type="text" class="form-control bg-light" 
                               value="{{ $transaksi->kode_transaksi }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Nama Barang</label>
                        <input type="text" class="form-control bg-light" 
                               value="{{ $detail->barang->nama_barang ?? 'Barang Terhapus' }}" readonly>
                        
                        {{-- ID Barang yang dikirim ke Controller (WAJIB data_barang_id) --}}
                        <input type="hidden" name="data_barang_id" value="{{ $detail->data_barang_id }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Merk</label>
                        <input type="text" class="form-control bg-light" 
                               value="{{ $detail->barang->merek ?? '-' }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_transaksi"
                               class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                               value="{{ old('tanggal_transaksi', $transaksi->tanggal_transaksi) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Lokasi <span class="text-danger">*</span></label>
                        <select name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" required>
                            <option value="Shopee" {{ old('lokasi', $transaksi->lokasi) == 'Shopee' ? 'selected' : '' }}>Shopee</option>
                            <option value="Tiktok Shop" {{ old('lokasi', $transaksi->lokasi) == 'Tiktok Shop' ? 'selected' : '' }}>Tiktok Shop</option>
                            <option value="Nephew Riders" {{ old('lokasi', $transaksi->lokasi) == 'Nephew Riders' ? 'selected' : '' }}>Nephew Riders</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Jumlah Keluar <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah"
                               class="form-control @error('jumlah') is-invalid @enderror"
                               value="{{ old('jumlah', $detail->jumlah) }}" min="1" required>
                        <small class="text-info mt-1 d-block">
                            <i class="fas fa-info-circle"></i> Stok saat ini di sistem: <strong>{{ $detail->barang->jml_stok ?? 0 }}</strong>
                        </small>
                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('kel_barang.b_keluar.index') }}" class="btn btn-secondary mr-2">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection