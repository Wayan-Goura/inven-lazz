@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> Mohon periksa kembali input Anda.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="mx-auto" style="max-width: 900px;">
        <div class="card shadow">
            
            {{-- HEADER: Mengikuti style Transaksi --}}
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Data Barang
                </h6>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
                </a>
            </div>

            <form action="{{ route('barang.store') }}" method="POST">
                @csrf

                {{-- BODY: Mengikuti style Transaksi (Tanpa inner-card putih) --}}
                <div class="card-body" style="max-height: calc(100vh - 260px); overflow-y: auto;">
                    <div class="row">

                        {{-- NAMA BARANG --}}
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Nama Barang <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" 
                                    class="form-control @error('nama_barang') is-invalid @enderror" 
                                    placeholder="Contoh: Bolpoin Hitam" required>
                                @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- MEREK --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Merek <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="merek" value="{{ old('merek') }}" 
                                    class="form-control @error('merek') is-invalid @enderror" 
                                    placeholder="Contoh: Joyko" required>
                                @error('merek') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- KATEGORI --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Pilih Kategori --</option>
                                    @foreach(\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->nama_category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- KODE BARANG --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Kode Barang <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="k_barang" value="{{ old('k_barang') }}" 
                                    class="form-control @error('k_barang') is-invalid @enderror" 
                                    placeholder="BRG001" required>
                                @error('k_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- JUMLAH STOK --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-gray-700">
                                    Jumlah Stok <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="jml_stok" value="{{ old('jml_stok') }}" 
                                    class="form-control @error('jml_stok') is-invalid @enderror" 
                                    placeholder="0" min="0" required>
                                @error('jml_stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FOOTER: Mengikuti style Transaksi --}}
                <div class="card-footer bg-white d-flex justify-content-end">
                    <button type="reset" class="btn btn-light mr-2">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection