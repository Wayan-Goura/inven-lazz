@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Edit Barang Return</h1>

    <div class="card shadow">
        <div class="card-body">

            <form action="{{ route('kel_barang.b_return.update', $barang->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Kode Barang</label>
                        <input type="text" name="kode" class="form-control"
                               value="{{ $barang->kode }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nama Barang</label>
                        <input type="text" name="nama" class="form-control"
                               value="{{ $barang->nama }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Merk</label>
                        <input type="text" name="merk" class="form-control"
                               value="{{ $barang->merk }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Kategori</label>
                        <select name="kategori" class="form-control" required>
                            <option value="Helm" {{ $barang->kategori=='Helm'?'selected':'' }}>Helm</option>
                            <option value="Kaca" {{ $barang->kategori=='Kaca'?'selected':'' }}>Kaca</option>
                            <option value="Tali" {{ $barang->kategori=='Tali'?'selected':'' }}>Tali</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tanggal Return</label>
                        <input type="date" name="tanggal" class="form-control"
                               value="{{ $barang->tanggal }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" min="1"
                               class="form-control"
                               value="{{ $barang->jumlah }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label>Alasan</label>
                        <textarea name="alasan" class="form-control"
                                  rows="3">{{ $barang->alasan }}</textarea>
                    </div>

                </div>

                <div class="text-right">
                    <a href="{{ route('kel_barang.b_return.index') }}"
                       class="btn btn-secondary">
                        Batal
                    </a>
                    <button class="btn btn-warning">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
