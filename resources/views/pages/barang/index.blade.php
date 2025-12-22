@extends('layouts.app')
@section('content')

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box mr-2"></i> Data Barang
        </h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">

        <div class="card-header py-3 d-flex flex-wrap align-items-center">

            <div class="d-flex align-items-center">
                <a href="{{ route('barang.create') }}"
                   class="btn btn-sm btn-success mr-2 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Barang
                </a>

                <a href="{{ route('barang.cetak_pdf') }}"
                   target="_blank"
                   class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
            </div>

            <div class="ml-auto d-flex flex-wrap align-items-center">
                <input type="text"
                       data-search
                       class="form-control form-control-sm mr-2 shadow-sm"
                       style="width: 180px"
                       placeholder="Cari barang...">

                <input type="date"
                       data-filter-tanggal
                       class="form-control form-control-sm mr-2 shadow-sm"
                       style="width: 160px">

                <select id="categoryFilter"
                        data-filter-extra
                        class="form-control form-control-sm shadow-sm"
                        style="width: 180px">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->nama_category }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
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
                            <td class="text-center">
                                {{ $dataBarangs->firstItem() + $index }}
                            </td>
                            <td><code class="text-primary font-weight-bold">{{ $barang->k_barang }}</code></td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->merek }}</td>
                            <td data-category-id="{{ $barang->category_id ?? '' }}">
                                {{ $barang->category->nama_category ?? '—' }}
                            </td>
                            <td class="text-center">
                                {{-- LOGIKA WARNA STOK --}}
                                @if($barang->jml_stok < 10)
                                    <span class="badge badge-danger px-3 py-2 shadow-sm" title="Stok Kritis!">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $barang->jml_stok }}
                                    </span>
                                @elseif($barang->jml_stok >= 10 && $barang->jml_stok <= 20)
                                    <span class="badge badge-warning px-3 py-2 shadow-sm" title="Stok Menipis">
                                        <i class="fas fa-info-circle mr-1"></i> {{ $barang->jml_stok }}
                                    </span>
                                @else
                                    <span class="badge badge-success px-3 py-2 shadow-sm" title="Stok Aman">
                                        <i class="fas fa-check-circle mr-1"></i> {{ $barang->jml_stok }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="small">{{ $barang->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('barang.edit', $barang->id) }}"
                                   class="btn btn-sm btn-info mb-1 shadow-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('barang.destroy', $barang->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus barang {{ $barang->nama_barang }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger mb-1 shadow-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                Tidak ada data barang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <p class="small text-muted mb-0">
                    Menampilkan {{ $dataBarangs->firstItem() }} sampai {{ $dataBarangs->lastItem() }} dari {{ $dataBarangs->total() }} data
                </p>
                <div>
                    {{ $dataBarangs->links() }}
                </div>
            </div>
        </div>

    </div>
</div>

{{-- FILTER SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("[data-search]");
    const dateInput = document.querySelector("[data-filter-tanggal]");
    const categoryInput = document.querySelector("#categoryFilter");
    const tableRows = document.querySelectorAll("#barangTable tbody tr");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const dateValue = dateInput.value;
        const categoryValue = categoryInput.value;

        tableRows.forEach(row => {
            // Memperbaiki index children agar sesuai dengan urutan kolom
            const kode = row.children[1]?.innerText.toLowerCase() || '';
            const nama = row.children[2]?.innerText.toLowerCase() || '';
            const categoryCell = row.children[4]; // Kolom Kategori
            const categoryId = categoryCell?.getAttribute('data-category-id') || '';
            const tanggal = row.children[6]?.innerText.trim() || ''; 

            let show = true;

            // Pencarian Kode atau Nama
            if (searchValue && !kode.includes(searchValue) && !nama.includes(searchValue)) show = false;
            
            // Filter Kategori
            if (categoryValue && categoryId !== categoryValue) show = false;
            
            // Filter Tanggal (Opsional: Butuh format yang presisi jika ingin filter date input)
            // if (dateValue && !tanggal.includes(dateValue)) show = false;

            row.style.display = show ? "" : "none";
        });
    }

    searchInput.addEventListener("keyup", filterTable);
    dateInput.addEventListener("change", filterTable);
    categoryInput.addEventListener("change", filterTable);
});
</script>

@endsection