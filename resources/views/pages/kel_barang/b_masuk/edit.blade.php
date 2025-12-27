@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <h5 class="mb-3 text-gray-800 font-weight-bold">
        Edit Barang Masuk
    </h5>

    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
        @csrf
        @method('PUT')

        @php
            $detail = $transaksi->detailTransaksis->first();
            $barang = $detail->barang ?? null;
        @endphp

        <div class="row">

            <!-- KODE TRANSAKSI -->
            <div class="col-md-6 mb-3">
                <label>Kode Transaksi</label>
                <input type="text" class="form-control" value="{{ $transaksi->kode_transaksi }}" readonly>
            </div>

            <!-- NAMA BARANG -->
            <div class="col-md-6 mb-3">
                <label>Nama Barang</label>
                <input type="text" class="form-control" value="{{ $barang->nama_barang ?? '' }}" readonly>
            </div>

            <!-- MERK -->
            <div class="col-md-6 mb-3">
                <label>Merk</label>
                <input type="text" class="form-control" value="{{ $barang->merek ?? '' }}" readonly>
            </div>

            <!-- KATEGORI -->
            <div class="col-md-6 mb-3">
                <label>Kategori</label>
                <input type="text" class="form-control" value="{{ $barang->category->name ?? '' }}" readonly>
            </div>

            <!-- TANGGAL -->
            <div class="col-md-6 mb-3">
                <label>Tanggal Masuk</label>
                <input type="date" class="form-control" value="{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d') }}" readonly>
            </div>

            <!-- JUMLAH / STOCK -->
            <div class="col-md-6 mb-3">
                <label>Jumlah Stock</label>
                <input type="number" name="jumlah" class="form-control" value="{{ $detail->jumlah }}" required>
            </div>

            <!-- LOKASI -->
            <div class="col-md-6 mb-3">
                <label>Lokasi</label>
                <input type="text" class="form-control" value="{{ $transaksi->lokasi ?? '' }}" readonly>
            </div>

        </div>

        <div class="text-right mt-3">
            <a href="{{ route('kel_barang.b_masuk.index') }}" class="btn btn-secondary btn-sm">Batal</a>
            <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </div>
    </form>
</div>

@endsection
