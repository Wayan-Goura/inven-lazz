@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- JUDUL -->
    <h1 class="h3 mb-4 text-gray-800">Kategori</h1>

    <!-- ALERT SUCCESS -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- BAR ATAS -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">

            <!-- KIRI -->
            <div>
                <a href="{{ route('kel_barang.catagory.create') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>

                <a href="#" class="btn btn-sm btn-primary ml-2">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>

            <!-- KANAN -->
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

    <!-- TABEL -->
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

                                <form action="{{ route('kel_barang.catagory.destroy', $category->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
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

{{-- JS SEARCH --}}
<script>
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
