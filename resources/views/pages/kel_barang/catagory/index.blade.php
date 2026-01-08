@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Kategori</h1>

    {{-- SweetAlert Notifikasi Sukses --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        </script>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">

            <div>
                <a href="{{ route('kel_barang.catagory.create') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>

                <a href="#" class="btn btn-sm btn-primary ml-2">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>

            <div>
                <input
                    type="text"
                    class="form-control form-control-sm"
                    placeholder="Cari kategori..."
                    style="width: 200px;"
                    data-search
                >
            </div>

        </div>
    </div>

    <div class="card shadow">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="60">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center" width="160">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td class="text-center">
                                {{ $index + 1 }}
                            </td>
                            <td>{{ $category->nama_category }}</td>
                            <td>{{ $category->deskripsi ?? '-' }}</td>
                            <td class="text-center">

                                <a href="{{ route('kel_barang.catagory.edit', $category->id) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form id="delete-form-{{ $category->id }}" 
                                      action="{{ route('kel_barang.catagory.destroy', $category->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete('{{ $category->id }}', '{{ $category->nama_category }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Data kategori belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Fungsi Konfirmasi Hapus
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Hapus Kategori?',
        text: "Anda akan menghapus kategori: " + name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("[data-search]");
    const tableRows = document.querySelectorAll("tbody tr");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? "" : "none";
        });
    }
    searchInput.addEventListener("input", filterTable);
});
</script>
@endsection