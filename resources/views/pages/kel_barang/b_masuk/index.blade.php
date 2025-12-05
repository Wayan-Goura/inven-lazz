@extends('layouts.app')

@section('content')

<div class="px-6 py-4">

    <h1 class="text-2xl font-bold mb-4">Barang Masuk</h1>

    <!-- BARIS ATAS -->
    <div class="flex justify-between items-center mb-4 flex-wrap gap-3">

        <!-- Tombol kiri -->
        <div class="flex gap-2">
            <!-- Buka modal create -->
            <a href="#" 
                class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md shadow"
                onclick="openAddModal()">
                + Tambah Barang Masuk
                
            </a>

            <a href="#" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md shadow">
                Cetak PDF
            </a>
        </div>

        <!-- Search + Filter -->
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
                <option value="">Merk</option>
                <option value="bogo">Bogo</option>
                <option value="nhk">NHK</option>
                <option value="gm">GM</option>
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
                    <th class="p-3 border">Tanggal Masuk</th>
                    <th class="p-3 border">Jumlah</th>
                    <th class="p-3 border text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-sm">

                <tr>
                    <td class="p-3 border text-center">1</td>
                    <td class="p-3 border">BRG001</td>
                    <td class="p-3 border">Helm Bogo Retro</td>
                    <td class="p-3 border">Bogo</td>
                    <td class="p-3 border">2025-01-10</td>
                    <td class="p-3 border text-center">15</td>

                    <td class="p-3 border text-center flex gap-2 justify-center">
                        <button 
                            onclick="openModal('{{ route('barang.masuk.edit', ['id' => 1]) }}')" 
                            class="px-2 py-1 bg-blue-600 text-white rounded text-xs">
                            Edit
                        </button>
                        <a href="#" class="px-2 py-1 bg-red-600 text-white rounded text-xs">Hapus</a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

<!-- =====================
     MODAL FORM
=========================-->
<div id="modalForm" 
     class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white p-5 rounded-lg shadow-lg w-[500px] max-h-[90vh] overflow-y-auto">

        <!-- AJAX load content -->
        <div id="modalContent"></div>

        <div class="mt-4 text-right">
            <button onclick="closeModal()" class="px-3 py-1 bg-gray-500 text-white rounded">
                Tutup
            </button>
        </div>
    </div>
</div>


{{-- JS Modal + Search --}}
<script>
function openModal(url) {
    const modal = document.getElementById("modalForm");
    const content = document.getElementById("modalContent");

    modal.classList.remove("hidden");
    content.innerHTML = "<p class='text-center py-5'>Loading...</p>";

    fetch(url)
        .then(res => res.text())
        .then(html => content.innerHTML = html);
}

function closeModal() {
    document.getElementById("modalForm").classList.add("hidden");
    document.getElementById("modalContent").innerHTML = "";
}

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
