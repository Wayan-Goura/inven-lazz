@extends('layouts.app')
@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Mohon periksa kembali input Anda.
        </div>
    @endif

    <div class="mx-auto" style="max-width: 900px;">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between">
                <h6 class="font-weight-bold text-primary"><i class="fas fa-plus-circle"></i> Tambah Data Barang</h6>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
            </div>

            <form id="formTambahBarang" action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="card-body" style="max-height: 60vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Kode Barang <span class="text-danger">*</span></label>
                                <select id="kodeSelect" class="form-control mb-2">
                                    <option value="">-- Pilih Kode Barang --</option>
                                    <option value="__new">+ Tambah Kode Baru</option>
                                    @foreach($existingBarangs as $barang)
                                        <option value="{{ $barang->k_barang }}" data-nama="{{ $barang->nama_barang }}" data-merek="{{ $barang->merek }}" data-category="{{ $barang->category_id }}">
                                            {{ $barang->k_barang }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="k_barang" id="kodeInput" class="form-control d-none" placeholder="Contoh: BRG005">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" id="namaBarang" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Merek</label>
                                <input type="text" name="merek" id="merekBarang" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category_id" id="categoryBarang" class="form-control">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama_category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Stok</label>
                                <input type="number" name="jml_stok" class="form-control" min="1" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right bg-white">
                    <button type="button" class="btn btn-primary" onclick="confirmSave()">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmSave() {
    Swal.fire({
        title: 'Simpan Data?',
        text: "Pastikan data barang sudah benar.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formTambahBarang').submit();
        }
    });
}

// Logika Asli Pemilihan Kode
document.addEventListener("DOMContentLoaded", function () {
    const kodeSelect = document.getElementById("kodeSelect");
    const kodeInput = document.getElementById("kodeInput");
    const nama = document.getElementById("namaBarang");
    const merek = document.getElementById("merekBarang");
    const kategori = document.getElementById("categoryBarang");

    kodeSelect.addEventListener("change", function () {
        const selected = kodeSelect.options[kodeSelect.selectedIndex];
        if (selected.value === "__new") {
            kodeSelect.classList.add("d-none");
            kodeInput.classList.remove("d-none");
            kodeInput.value = ""; kodeInput.required = true; kodeInput.focus();
            nama.value = ""; merek.value = ""; kategori.value = "";
            nama.readOnly = false; merek.readOnly = false; kategori.disabled = false;
        } else if (selected.value !== "") {
            kodeInput.value = selected.value;
            nama.value = selected.dataset.nama;
            merek.value = selected.dataset.merek;
            kategori.value = selected.dataset.category;
            nama.readOnly = true; merek.readOnly = true; kategori.disabled = true;
        }
    });
});
</script>
@endsection