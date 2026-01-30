@extends('layouts.app')

<style>
    .table-scroll-area {
        position: relative;
        max-height: 410px; 
        overflow-y: auto; 
        overflow-x: auto; 
        border: 1px solid #e3e6f0;
    }

    .table-scroll-area table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-scroll-area table thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fc !important;
        z-index: 10;
        box-shadow: inset 0 -1px 0 #e3e6f0;
        border-top: 0;
    }
</style>
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
        <div class="card-body">
            <div class="table-scroll-area">
                <table class="table table-bordered table-hover mb-0">
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
            <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <small class="text-muted mr-3">
                    @if ($categories->count() > 0)
                        Menampilkan {{ $categories->firstItem() ?? 1 }} –
                        {{ $categories->lastItem() ?? $categories->count() }}
                        dari {{ $categories->total() ?? $categories->count() }} data
                    @else
                        Tidak ada data
                    @endif
                </small>

                {{-- SHOW PER PAGE --}}
                <form method="GET">
                    <select name="per_page"
                            class="form-control form-control-sm"
                            onchange="this.form.submit()">

                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>
                            Semua
                        </option>
                    </select>
                </form>
            </div>
    {{-- PAGINATION --}}
    <div>
        @if(request('per_page') !== 'all')
            {{ $categories->withQueryString()->links() }}
        @endif
    </div>
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