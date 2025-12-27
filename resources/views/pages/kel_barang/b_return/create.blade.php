@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Tambah Barang Return</h1>

    <div class="card shadow">
        <div class="card-body">

            <form action="{{ route('kel_barang.b_return.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nama Barang</label>
                        <select name="barang_id" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            {{-- Ganti $category->name jika nama kolom di DB Anda berbeda --}}
                            <option value="{{ $category->id }}">
                                {{ $category->name ?? $category->nama_category ?? 'Nama Tidak Ditemukan' }}
                            </option>
                        @endforeach
                        </select>                         
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tanggal Return</label>
                        <input type="date" name="tanggal_return" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah_return" min="1"
                               class="form-control" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label>Alasan (Deskripsi)</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>

                </div>

                <div class="text-right">
                    <a href="{{ route('kel_barang.b_return.index') }}"
                       class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection