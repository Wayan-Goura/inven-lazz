@extends('layouts.app')
@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> Mohon periksa kembali input Anda.
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="mx-auto" style="max-width: 900px;">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit mr-2"></i> Edit Data Barang</h6>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
            </div>

            <form id="formEditBarang" action="{{ route('barang.update', $barang->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body" style="max-height: 60vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" name="k_barang" value="{{ old('k_barang', $barang->k_barang) }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Merek <span class="text-danger">*</span></label>
                                <input type="text" name="merek" value="{{ old('merek', $barang->merek) }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $barang->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->nama_category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Jumlah Stok <span class="text-danger">*</span></label>
                                <input type="number" name="jml_stok" min="0" value="{{ old('jml_stok', $barang->jml_stok) }}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('barang.index') }}" class="btn btn-light mr-2">Batal</a>
                    <button type="button" class="btn btn-primary" onclick="confirmUpdate()">
                        <i class="fas fa-save mr-1"></i> Update Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmUpdate() {
    Swal.fire({
        title: 'Update Data?',
        text: "Simpan perubahan pada barang ini?",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEditBarang').submit();
        }
    });
}
</script>
@endsection