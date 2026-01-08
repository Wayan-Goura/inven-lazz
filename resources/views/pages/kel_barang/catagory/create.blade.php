@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Category</h5>
                </div>

                <div class="card-body">
                    <form id="formCreateCategory" action="{{ route('kel_barang.catagory.store') }}" method="POST">
                        @csrf

                        {{-- Nama Category --}}
                        <div class="mb-3">
                            <label for="nama_category" class="form-label">
                                Nama Category <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="nama_category" 
                                id="nama_category"
                                class="form-control @error('nama_category') is-invalid @enderror"
                                value="{{ old('nama_category') }}"
                                placeholder="Masukkan nama category"
                                required
                            >
                            @error('nama_category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">
                                Deskripsi
                            </label>
                            <textarea 
                                name="deskripsi" 
                                id="deskripsi"
                                rows="4"
                                class="form-control @error('deskripsi') is-invalid @enderror"
                                placeholder="Masukkan deskripsi (opsional)"
                            >{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Button --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kel_barang.catagory.index') }}" class="btn btn-secondary">
                                Kembali
                            </a>

                            <button type="button" class="btn btn-primary" onclick="confirmSave()">
                                Simpan Category
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmSave() {
    const name = document.getElementById('nama_category').value;
    
    if (!name) {
        Swal.fire('Oops!', 'Nama kategori wajib diisi.', 'error');
        return;
    }

    Swal.fire({
        title: 'Simpan Kategori?',
        text: "Pastikan nama kategori sudah sesuai.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formCreateCategory').submit();
        }
    });
}
</script>
@endsection