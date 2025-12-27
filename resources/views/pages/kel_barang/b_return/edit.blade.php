@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Edit Barang Return</h1>

    <div class="card shadow">
        <div class="card-body">

            {{-- Menggunakan $return->id sesuai dengan data yang dilempar dari controller --}}
            <form action="{{ route('kel_barang.b_return.update', $return->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nama Barang</label>
                        <select name="barang_id" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" 
                                    {{ $return->barang_id == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ $return->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tanggal Return</label>
                        {{-- Kolom database: tanggal_return --}}
                        <input type="date" name="tanggal_return" class="form-control"
                               value="{{ $return->tanggal_return }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jumlah</label>
                        {{-- Kolom database: jumlah_return --}}
                        <input type="number" name="jumlah_return" min="1"
                               class="form-control"
                               value="{{ $return->jumlah_return }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label>Alasan (Deskripsi)</label>
                        {{-- Kolom database: deskripsi --}}
                        <textarea name="deskripsi" class="form-control"
                                  rows="3">{{ $return->deskripsi }}</textarea>
                    </div>

                </div>

                <div class="text-right">
                    <a href="{{ route('kel_barang.b_return.index') }}"
                       class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-warning">
                        Update Data
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection