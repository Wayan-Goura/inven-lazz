@extends('layouts.app')
@section('content')

<div class="container-fluid">

    <!-- PAGE TITLE -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box mr-2"></i> Data Barang
        </h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- CARD -->
    <div class="card shadow mb-4">

        <!-- CARD HEADER -->
        <div class="card-header py-3 d-flex flex-wrap align-items-center">

            <!-- LEFT: BUTTON -->
            <div class="d-flex align-items-center">
                <a href="{{ route('barang.create') }}"
                   class="btn btn-sm btn-success mr-2">
                    <i class="fas fa-plus mr-1"></i> Tambah Barang
                </a>

                <a href="{{ route('barang.cetak_pdf') }}"
                   target="_blank"
                   class="btn btn-sm btn-primary">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
            </div>

            <!-- RIGHT: FILTER -->
            <div class="ml-auto d-flex flex-wrap align-items-center">

                <input type="text"
                       data-search
                       class="form-control form-control-sm mr-2"
                       style="width: 180px"
                       placeholder="Cari barang...">

                <input type="date"
                       data-filter-tanggal
                       class="form-control form-control-sm mr-2"
                       style="width: 160px">

                <select id="categoryFilter"
                        data-filter-extra
                        class="form-control form-control-sm"
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

        <!-- CARD BODY -->
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
                            <th width="80">Stok</th>
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
                            <td>{{ $barang->k_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->merek }}</td>
                            <td data-category-id="{{ $barang->category_id ?? '' }}">
                                {{ $barang->category->nama_category ?? '—' }}
                            </td>
                            <td class="text-center font-weight-bold">
                                {{ $barang->jml_stok }}
                            </td>
                            <td class="text-center">
                                {{ $barang->created_at->format('Y-m-d') }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('barang.barang.edit' , $barang->id)}}"
                                   class="btn btn-sm btn-warning mb-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('barang.destroy', $barang->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus barang {{ $barang->nama_barang }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Tidak ada data barang.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <!-- CARD FOOTER -->
        <div class="card-footer">
            {{ $dataBarangs->links() }}
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
            const kode = row.children[1]?.innerText.toLowerCase() || '';
            const nama = row.children[2]?.innerText.toLowerCase() || '';
            const categoryId = row.children[3]?.dataset.categoryId || '';
            const tanggal = row.children[5]?.innerText || '';

            let show = true;

            if (searchValue && !kode.includes(searchValue) && !nama.includes(searchValue)) show = false;
            if (categoryValue && categoryId !== categoryValue) show = false;
            if (dateValue && tanggal !== dateValue) show = false;

            row.style.display = show ? "" : "none";
        });
    }

    searchInput.addEventListener("input", filterTable);
    dateInput.addEventListener("change", filterTable);
    categoryInput.addEventListener("change", filterTable);
});
</script>

@endsection
