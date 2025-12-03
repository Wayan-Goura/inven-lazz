@extends('layouts.app')

@section('content')
<div class="px-6 py-4">

    <h1 class="text-2xl font-bold mb-4">Data Barang</h1>

    <!-- Baris aksi atas: Tambah + Cetak/Search/Filter -->
    <div class="flex flex-wrap justify-between items-center mb-4 gap-4">

        <!-- Tombol Tambah + Cetak PDF -->
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('barang.create') }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                + Tambah Barang
            </a>

            <a href="#" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md shadow">
                Cetak PDF
            </a>
        </div>

        <!-- Search dan Filter -->
        <div class="flex gap-2 flex-wrap items-center">
            <!-- Search -->
            <input type="text" data-search id="searchInput" placeholder="Search..." 
                   class="border px-2 py-1 rounded">

            <!-- Filter Tanggal -->
            <input type="date" data-filter-tanggal id="dateFilter" class="border px-2 py-1 rounded">

            <!-- Filter Merk -->
            <select data-filter-extra id="merkFilter" class="border px-2 py-1 rounded">
                <option value="">Filter Merk</option>
                <option value="Standard">Standard</option>
                <option value="Sidu">Sidu</option>
                <option value="Kenko">Kenko</option>
            </select>
        </div>
    </div>

    <!-- TABEL DATA BARANG -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full border-collapse" id="barangTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Kode Barang</th>
                    <th class="p-3 border">Nama Barang</th>
                    <th class="p-3 border">Merk</th>
                    <th class="p-3 border">Harga Beli</th>
                    <th class="p-3 border">Harga Jual</th>
                    <th class="p-3 border">Stok</th>
                    <th class="p-3 border">Tanggal</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $barangs = [
                        ['kode'=>'BRG001','nama'=>'Bolpoin Hitam','merk'=>'Standard','harga_beli'=>2000,'harga_jual'=>3000,'stok'=>120,'tanggal'=>'2025-12-01'],
                        ['kode'=>'BRG002','nama'=>'Buku Tulis','merk'=>'Sidu','harga_beli'=>4500,'harga_jual'=>6500,'stok'=>80,'tanggal'=>'2025-12-02'],
                        ['kode'=>'BRG003','nama'=>'Spidol Whiteboard','merk'=>'Kenko','harga_beli'=>6000,'harga_jual'=>9000,'stok'=>45,'tanggal'=>'2025-12-03'],
                    ];
                @endphp

                @foreach($barangs as $index => $barang)
                <tr>
                    <td class="p-3 border text-center">{{ $index + 1 }}</td>
                    <td class="p-3 border">{{ $barang['kode'] }}</td>
                    <td class="p-3 border">{{ $barang['nama'] }}</td>
                    <td class="p-3 border">{{ $barang['merk'] }}</td>
                    <td class="p-3 border">{{ number_format($barang['harga_beli'],0,',','.') }}</td>
                    <td class="p-3 border">{{ number_format($barang['harga_jual'],0,',','.') }}</td>
                    <td class="p-3 border text-center">{{ $barang['stok'] }}</td>
                    <td class="p-3 border text-center">{{ $barang['tanggal'] }}</td>
                    <td class="p-3 border text-center flex gap-2 justify-center">
                        <a href="{{ route('barang.edit') }}" 
                           class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Edit
                        </a>
                        <button type="button" onclick="alert('Barang dihapus (dummy)')" 
                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("[data-search]");
    const dateInput = document.querySelector("[data-filter-tanggal]");
    const merkInput = document.querySelector("[data-filter-extra]");
    const tableRows = document.querySelectorAll("#barangTable tbody tr");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const dateValue = dateInput.value;
        const merkValue = merkInput.value.toLowerCase();

        tableRows.forEach(row => {
            const kode = row.children[1].innerText.toLowerCase();
            const nama = row.children[2].innerText.toLowerCase();
            const merk = row.children[3].innerText.toLowerCase();
            const tanggal = row.children[7].innerText;
            let show = true;

            // Filter search
            if (searchValue && !(kode.includes(searchValue) || nama.includes(searchValue))) show = false;

            // Filter merk
            if (merkValue && merk !== merkValue) show = false;

            // Filter tanggal
            if (dateValue && tanggal !== dateValue) show = false;

            row.style.display = show ? "" : "none";
        });
    }

    // Event search → reset filter date & merk
    searchInput.addEventListener("input", function () {
        dateInput.value = "";
        merkInput.value = "";
        filterTable();
    });

    // Event filter date → reset search & merk
    dateInput.addEventListener("change", function () {
        searchInput.value = "";
        merkInput.value = "";
        filterTable();
    });

    // Event filter merk → reset search & date
    merkInput.addEventListener("change", function () {
        searchInput.value = "";
        dateInput.value = "";
        filterTable();
    });
});
</script>

@endsection
