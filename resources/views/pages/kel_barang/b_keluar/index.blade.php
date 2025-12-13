@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Barang Keluar</h1>

    <!-- TOP BAR -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">

            <!-- KIRI -->
            <div>
                <a href="#" class="btn btn-sm btn-secondary">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>

            <!-- KANAN -->
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm"
                       placeholder="Cari barang..." data-search>

                <select class="form-control form-control-sm" data-filter-tanggal>
                    <option value="">Tanggal</option>
                    <option value="today">Hari ini</option>
                    <option value="week">Minggu ini</option>
                    <option value="month">Bulan ini</option>
                </select>

                <select class="form-control form-control-sm" data-filter-extra>
                    <option value="">Lokasi</option>
                    <option value="ubud">Ubud</option>
                    <option value="batubulan">Batubulan</option>
                    <option value="klungkung">Klungkung</option>
                </select>
            </div>

        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Merk</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th class="text-center">Jumlah</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>BRG001</td>
                    <td>Bolpoin Hitam</td>
                    <td>Standard</td>
                    <td>2025-01-13</td>
                    <td>Ubud</td>
                    <td class="text-center">20</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- JS TETAP --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("[data-search]");
    const filterTanggal = document.querySelector("[data-filter-tanggal]");
    const filterExtra = document.querySelector("[data-filter-extra]");
    const tableRows = document.querySelectorAll("tbody tr");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const tanggalValue = filterTanggal.value;
        const extraValue = filterExtra.value.toLowerCase();

        const today = new Date();
        const startOfWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay());
        const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

        tableRows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            const tanggalText = row.children[4]?.innerText ?? "";
            const rowTanggal = new Date(tanggalText);

            let show = true;

            if (searchValue && !rowText.includes(searchValue)) show = false;
            if (tanggalValue === "today" && rowTanggal.toDateString() !== today.toDateString()) show = false;
            if (tanggalValue === "week" && rowTanggal < startOfWeek) show = false;
            if (tanggalValue === "month" && rowTanggal < startOfMonth) show = false;
            if (extraValue && !rowText.includes(extraValue)) show = false;

            row.style.display = show ? "" : "none";
        });
    }

    searchInput.addEventListener("input", () => {
        filterTanggal.value = "";
        filterExtra.value = "";
        filterTable();
    });

    filterTanggal.addEventListener("change", () => {
        searchInput.value = "";
        filterExtra.value = "";
        filterTable();
    });

    filterExtra.addEventListener("change", () => {
        searchInput.value = "";
        filterTanggal.value = "";
        filterTable();
    });
});
</script>

@endsection
