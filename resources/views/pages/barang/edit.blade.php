@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Mohon periksa kembali input Anda.
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="mx-auto" style="max-width: 900px;">
        <div class="card shadow">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit mr-2"></i> Edit Data Barang
                </h6>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- BODY (SCROLLABLE) --}}
                <div class="card-body" style="max-height: 60vh; overflow-y: auto;">
                    <div class="row">

                        {{-- NAMA BARANG --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Nama Barang <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="nama_barang"
                                    value="{{ old('nama_barang', $barang->nama_barang) }}"
                                    class="form-control @error('nama_barang') is-invalid @enderror"
                                    required>
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- KODE BARANG --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Kode Barang <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="k_barang"
                                    value="{{ old('k_barang', $barang->k_barang) }}"
                                    class="form-control @error('k_barang') is-invalid @enderror"
                                    required>
                                @error('k_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- MEREK --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Merek <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="merek"
                                    value="{{ old('merek', $barang->merek) }}"
                                    class="form-control @error('merek') is-invalid @enderror"
                                    required>
                                @error('merek')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- KATEGORI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select name="category_id"
                                    class="form-control @error('category_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $barang->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->nama_category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- STOK --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    Jumlah Stok <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                    name="jml_stok"
                                    min="0"
                                    value="{{ old('jml_stok', $barang->jml_stok) }}"
                                    class="form-control @error('jml_stok') is-invalid @enderror"
                                    required>
                                @error('jml_stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('barang.index') }}" class="btn btn-light mr-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update Barang
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
