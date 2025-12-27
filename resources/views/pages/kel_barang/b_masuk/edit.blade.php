@extends('layouts.app')

@section('content')

{{-- resources/views/pages/kel_barang/b_masuk/edit.blade.php --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
    </div>
@endif
<form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        {{-- Pastikan nama input sesuai dengan $request->validate di controller --}}
        <div class="col-md-6 mb-3">
            <label>Nama Barang</label>
            <select name="data_barang_id" class="form-control">
                @foreach($barangs as $b)
                    <option value="{{ $b->id }}" {{ $transaksi->detailTransaksis->first()->data_barang_id == $b->id ? 'selected' : '' }}>
                        {{ $b->nama_barang }} (Stok: {{ $b->jml_stok }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label>Tanggal Transaksi</label>
            <input type="date" name="tanggal_transaksi" class="form-control" value="{{ $transaksi->tanggal_transaksi }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" value="{{ $transaksi->detailTransaksis->first()->jumlah }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ $transaksi->lokasi }}">
        </div>
    </div>

    <div class="text-right">
        <a href="{{ route('kel_barang.b_masuk.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Update Data</button>
    </div>
</form>

@endsection
