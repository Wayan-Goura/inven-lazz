@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Edit Kategori</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Kategori</h6>
        </div>
        <div class="card-body">
            
            <form id="formEditCategory" action="{{ route('kel_barang.catagory.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        
                        <div class="form-group">
                            <label for="nama_category">Nama Kategori <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="nama_category" 
                                id="nama_category" 
                                class="form-control @error('nama_category') is-invalid @enderror" 
                                value="{{ old('nama_category', $category->nama_category) }}" 
                                required
                            >
                            @error('nama_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea 
                                name="deskripsi" 
                                id="deskripsi" 
                                rows="4" 
                                class="form-control @error('deskripsi') is-invalid @enderror"
                            >{{ old('deskripsi', $category->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-primary mr-2" onclick="confirmUpdate()">
                        <i class="fas fa-save mr-1"></i> Update Kategori
                    </button>
                    <a href="{{ route('kel_barang.catagory.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmUpdate() {
    Swal.fire({
        title: 'Update Kategori?',
        text: "Simpan perubahan pada kategori ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEditCategory').submit();
        }
    });
}
</script>

@endsection