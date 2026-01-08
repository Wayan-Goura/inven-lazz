@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Edit Barang Return</h1>

    {{-- Alert untuk menangkap error validasi atau database --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">

            <form id="formEditReturn" action="{{ route('kel_barang.b_return.update', $return->id) }}" method="POST">
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
                        <input type="date" name="tanggal_return" class="form-control"
                               value="{{ $return->tanggal_return }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah_return" min="1"
                               class="form-control"
                               value="{{ $return->jumlah_return }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label>Alasan (Deskripsi)</label>
                        <textarea name="deskripsi" class="form-control"
                                  rows="3">{{ $return->deskripsi }}</textarea>
                    </div>
                </div>

                <div class="text-right">
                    <a href="{{ route('kel_barang.b_return.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="button" class="btn btn-warning" onclick="confirmUpdate()">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Pastikan SweetAlert2 terpasang --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmUpdate() {
    const form = document.getElementById('formEditReturn');
    
    // Validasi HTML5 dasar
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    Swal.fire({
        title: 'Update Data?',
        text: "Simpan perubahan data return ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f6c23e',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endsection