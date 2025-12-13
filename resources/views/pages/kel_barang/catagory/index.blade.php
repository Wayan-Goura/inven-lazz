@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- JUDUL -->
    <h1 class="h3 mb-4 text-gray-800">Kategori</h1>

    <!-- BAR ATAS -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">

            <!-- KIRI: TOMBOL -->
            <div>
                <a href="#" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>

                <a href="#" class="btn btn-sm btn-primary ml-2">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>

            <!-- KANAN: SEARCH -->
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
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center" width="160">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>KAT001</td>
                        <td>Helm</td>
                        <td>Semua jenis helm (full face, half face, retro)</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">2</td>
                        <td>KAT002</td>
                        <td>Aksesoris Motor</td>
                        <td>Spion, lampu, aksesoris kecil motor</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">3</td>
                        <td>KAT003</td>
                        <td>Oli Mesin</td>
                        <td>Oli motor berbagai merek</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>

            </table>

        </div>
    </div>

</div>

{{-- JS SEARCH (TETAP, TIDAK DIHILANGKAN) --}}
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
