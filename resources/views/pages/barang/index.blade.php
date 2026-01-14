@extends('layouts.app')
@push('styles')
<style>
.table-scroll thead th {
    position: sticky;
    top: 0;
}
</style>
@endpush
@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box mr-2"></i> Data Barang
        </h1>
    </div>

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
        <div class="card-header py-3 d-flex flex-wrap align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('barang.create') }}" class="btn btn-sm btn-success mr-2 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Barang
                </a>
                <a href="{{ route('barang.cetak_pdf') }}" target="_blank" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
            </div>

            <div class="ml-auto d-flex flex-wrap align-items-center">
                <input type="text" data-search class="form-control form-control-sm mr-2 shadow-sm" style="width: 180px" placeholder="Cari barang...">
                <input type="date" data-filter-tanggal class="form-control form-control-sm mr-2 shadow-sm" style="width: 160px">
                <select id="categoryFilter" data-filter-extra class="form-control form-control-sm shadow-sm" style="width: 180px">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nama_category }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive overflow-auto" style="max-height: 450px">
                <table class="table table-bordered table-hover" id="barangTable" width="100%">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="50">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Merek</th>
                            <th>Kategori</th>
                            <th width="120">Stok</th>
                            <th width="140">Tanggal</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataBarangs as $index => $barang)
                        <tr>
                            <td class="text-center">{{ $dataBarangs->firstItem() + $index }}</td>
                            <td><code class="text-primary font-weight-bold">{{ $barang->k_barang }}</code></td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->merek }}</td>
                            <td data-category-id="{{ $barang->category_id ?? '' }}">{{ $barang->category->nama_category ?? '—' }}</td>
                            <td class="text-center">
                                @if($barang->jml_stok < 10)
                                    <span class="badge badge-danger px-3 py-2 shadow-sm"><i class="fas fa-exclamation-triangle mr-1"></i> {{ $barang->jml_stok }}</span>
                                @elseif($barang->jml_stok >= 10 && $barang->jml_stok <= 20)
                                    <span class="badge badge-warning px-3 py-2 shadow-sm"><i class="fas fa-info-circle mr-1"></i> {{ $barang->jml_stok }}</span>
                                @else
                                    <span class="badge badge-success px-3 py-2 shadow-sm"><i class="fas fa-check-circle mr-1"></i> {{ $barang->jml_stok }}</span>
                                @endif
                            </td>
                            <td class="text-center"><span class="small">{{ $barang->created_at->format('d M Y') }}</span></td>
                            <td class="text-center">
                                @if($barang->is_disetujui)
                                    <button class="btn btn-sm btn-warning mb-1 shadow-sm" title="Menunggu persetujuan" disabled><i class="fas fa-clock"></i></button>
                                @else
                                    <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-info mb-1 shadow-sm"><i class="fas fa-edit"></i></a>
                                @endif

                                <form id="delete-form-{{ $barang->id }}" action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger mb-1 shadow-sm" onclick="confirmDelete('{{ $barang->id }}', '{{ $barang->nama_barang }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
            <p class="small text-muted mb-0">Menampilkan {{ $dataBarangs->firstItem() }} sampai {{ $dataBarangs->lastItem() }}</p>
            {{ $dataBarangs->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Hapus Barang?',
        text: "Yakin ingin menghapus " + name + "?",
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

// Logic Filter Asli
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("[data-search]");
    const dateInput = document.querySelector("[data-filter-tanggal]");
    const categoryInput = document.querySelector("#categoryFilter");
    const tableRows = document.querySelectorAll("#barangTable tbody tr");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const categoryValue = categoryInput.value;
        tableRows.forEach(row => {
            const kode = row.children[1]?.innerText.toLowerCase() || '';
            const nama = row.children[2]?.innerText.toLowerCase() || '';
            const categoryId = row.children[4]?.getAttribute('data-category-id') || '';
            let show = true;
            if (searchValue && !kode.includes(searchValue) && !nama.includes(searchValue)) show = false;
            if (categoryValue && categoryId !== categoryValue) show = false;
            row.style.display = show ? "" : "none";
        });
    }
    searchInput.addEventListener("keyup", filterTable);
    categoryInput.addEventListener("change", filterTable);
});
</script>
@endsection