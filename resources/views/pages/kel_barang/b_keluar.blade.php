@extends('layouts.app')

@section('content')

<div class="px-6 py-4">

    <h1 class="text-2xl font-bold mb-4">Barang Keluar</h1>

    <!-- BARIS ATAS -->
    <div class="flex justify-between items-center mb-4 flex-wrap gap-3">

        <!-- KIRI -->
        <div class="flex gap-2">
            <a href="#" class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md shadow">
                + Tambah Barang Keluar
            </a>

            <a href="#" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md shadow">
                Cetak PDF
            </a>
        </div>

        <!-- KANAN -->
        <div class="flex gap-2">

            <input 
                type="text"
                placeholder="Cari barang..."
                class="px-3 py-1.5 border rounded-md text-sm w-48"
                data-search
            >

            <select class="px-3 py-1.5 border rounded-md text-sm" data-filter-tanggal>
                <option value="">Tanggal</option>
                <option value="today">Hari ini</option>
                <option value="week">Minggu ini</option>
                <option value="month">Bulan ini</option>
            </select>

            <select class="px-3 py-1.5 border rounded-md text-sm" data-filter-extra>
                <option value="">Lokasi</option>
                <option value="ubud">Ubud</option>
                <option value="batubulan">Batubulan</option>
                <option value="klungkung">Klungkung</option>
            </select>

        </div>
    </div>

    <!-- TABEL -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left text-sm">
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Kode Barang</th>
                    <th class="p-3 border">Nama Barang</th>
                    <th class="p-3 border">Merk</th>
                    <th class="p-3 border">Tanggal Keluar</th>
                    <th class="p-3 border">Lokasi</th>
                    <th class="p-3 border">Jumlah</th>
                    <th class="p-3 border text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-sm">

                <tr>
                    <td class="p-3 border text-center">1</td>
                    <td class="p-3 border">BRG001</td>
                    <td class="p-3 border">Bolpoin Hitam</td>
                    <td class="p-3 border">Standard</td>
                    <td class="p-3 border">2025-01-13</td>
                    <td class="p-3 border">Ubud</td>
                    <td class="p-3 border text-center">20</td>
                    <td class="p-3 border text-center">
                        <div class="flex gap-2 justify-center">
                            <a href="#" class="px-2 py-1 bg-blue-600 text-xs text-white rounded">Edit</a>
                            <a href="#" class="px-2 py-1 bg-red-600 text-xs text-white rounded">Hapus</a>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

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

            // SEARCH
            if (searchValue && !rowText.includes(searchValue)) show = false;

            // FILTER TANGGAL
            if (tanggalValue === "today" && rowTanggal.toDateString() !== today.toDateString()) show = false;
            if (tanggalValue === "week" && rowTanggal < startOfWeek) show = false;
            if (tanggalValue === "month" && rowTanggal < startOfMonth) show = false;

            // FILTER EXTRA
            if (extraValue && !rowText.includes(extraValue)) show = false;

            row.style.display = show ? "" : "none";
        });
    }

    // Saat search → reset semua filter
    searchInput.addEventListener("input", () => {
        filterTanggal.value = "";
        filterExtra.value = "";
        filterTable();
    });

    // Saat pilih filter tanggal → reset search & filter alasan
    filterTanggal.addEventListener("change", () => {
        searchInput.value = "";
        filterExtra.value = "";
        filterTable();
    });

    // Saat pilih alasan → reset search & filter tanggal
    filterExtra.addEventListener("change", () => {
        searchInput.value = "";
        filterTanggal.value = "";
        filterTable();
    });

});
</script>

@endsection
