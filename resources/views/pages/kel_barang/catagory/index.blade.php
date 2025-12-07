@extends('layouts.app')

@section('content')

<div class="px-6 py-4">

    <h1 class="text-2xl font-bold mb-4">Kategori</h1>

    <!-- BARIS ATAS -->
    <div class="flex justify-between items-center mb-4 flex-wrap gap-3">

        <!-- KIRI: tombol -->
        <div class="flex gap-2">
            <a href="#" class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md shadow">
                + Tambah Kategori
            </a>

            <a href="#" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md shadow">
                Cetak PDF
            </a>
        </div>

        <!-- KANAN: Search -->
        <div class="flex gap-2">

            <!-- Search -->
            <input 
                type="text"
                placeholder="Cari kategori..."
                class="px-3 py-1.5 border rounded-md text-sm w-48"
                data-search
            >
        </div>

    </div>

    <!-- TABEL DATA KATEGORI -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Kode Kategori</th>
                    <th class="p-3 border">Nama Kategori</th>
                    <th class="p-3 border">Deskripsi</th>
                    <th class="p-3 border text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td class="p-3 border text-center">1</td>
                    <td class="p-3 border">KAT001</td>
                    <td class="p-3 border">Helm</td>
                    <td class="p-3 border">Semua jenis helm (full face, half face, retro)</td>
                    <td class="p-3 border text-center flex gap-2 justify-center">
                        <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Edit</a>
                        <a href="#" class="px-3 py-1 bg-red-600 text-white rounded text-sm">Hapus</a>
                    </td>
                </tr>

                <tr>
                    <td class="p-3 border text-center">2</td>
                    <td class="p-3 border">KAT002</td>
                    <td class="p-3 border">Aksesoris Motor</td>
                    <td class="p-3 border">Spion, lampu, aksesoris kecil motor</td>
                    <td class="p-3 border text-center flex gap-2 justify-center">
                        <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Edit</a>
                        <a href="#" class="px-3 py-1 bg-red-600 text-white rounded text-sm">Hapus</a>
                    </td>
                </tr>

                <tr>
                    <td class="p-3 border text-center">3</td>
                    <td class="p-3 border">KAT003</td>
                    <td class="p-3 border">Oli Mesin</td>
                    <td class="p-3 border">Oli motor berbagai merek</td>
                    <td class="p-3 border text-center flex gap-2 justify-center">
                        <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Edit</a>
                        <a href="#" class="px-3 py-1 bg-red-600 text-white rounded text-sm">Hapus</a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

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
