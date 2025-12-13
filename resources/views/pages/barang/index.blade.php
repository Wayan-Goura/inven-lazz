@extends('layouts.app')

@section('content')
<div class="px-6 py-4">

    <h1 class="text-2xl font-bold mb-4">Data Barang</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header: Tombol & Filter -->
    <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('barang.create') }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                + Tambah Barang
            </a>

            <a href="{{ route('barang.cetak_pdf') }}" 
            target="_blank"  class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md shadow hover:bg-indigo-700 transition duration-150">
                Cetak PDF
            </a>
        </div>

        <div class="flex gap-2 flex-wrap items-center">
            <input type="text" data-search id="searchInput" placeholder="Cari barang..." 
                   class="border px-2 py-1 rounded">

            <input type="date" data-filter-tanggal id="dateFilter" class="border px-2 py-1 rounded">

            <select data-filter-extra id="categoryFilter" class="border px-2 py-1 rounded">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->nama_category }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full border-collapse" id="barangTable">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Kode Barang</th>   
                    <th class="p-3 border">Nama Barang</th>  
                    <th class="p-3 border">Kategori</th>
                    <th class="p-3 border">Stok</th>
                    <th class="p-3 border">Tanggal Dibuat</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($dataBarangs as $index => $barang)
                <tr>
                    <td class="p-3 border text-center">{{ $dataBarangs->firstItem() + $index }}</td>
                    <td class="p-3 border">{{ $barang->k_barang }}</td>
                    <td class="p-3 border">{{ $barang->nama_barang }}</td>
                    <td class="p-3 border" data-category-id="{{ $barang->category_id ?? '' }}">
                        @if($barang->category)
                            {{ $barang->category->nama_category }}
                        @else
                            <span class="text-gray-500">—</span>
                        @endif
                    </td>
                    <td class="p-3 border text-center">{{ $barang->jml_stok }}</td>
                    <td class="p-3 border text-center">{{ $barang->created_at->format('Y-m-d') }}</td>
                    <td class="p-3 border text-center flex flex-wrap gap-2 justify-center">
                        <a href="{{ route('barang.edit', $barang->id) }}" 
                           class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                            Edit
                        </a>
                        
                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" 
                              onsubmit="return confirm('Hapus barang \"{{ $barang->nama_barang }}\"?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-3 border text-center text-gray-500">
                        Tidak ada data barang.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $dataBarangs->links() }}
    </div>

</div>

{{-- JavaScript untuk Client-Side Filtering --}}
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
            // Kolom: 0=No, 1=Kode, 2=Nama, 3=Kategori, 4=Stok, 5=Tanggal, 6=Aksi
            const kode = row.children[1]?.innerText?.toLowerCase() || '';
            const nama = row.children[2]?.innerText?.toLowerCase() || '';
            const categoryCell = row.children[3];
            const categoryId = categoryCell.dataset.categoryId || '';
            const tanggal = row.children[5]?.innerText || '';

            let show = true;

            // Filter search: cocokkan di Kode atau Nama
            if (searchValue && !kode.includes(searchValue) && !nama.includes(searchValue)) {
                show = false;
            }

            // Filter kategori berdasarkan ID (value dari select)
            if (categoryValue && categoryId !== categoryValue) {
                show = false;
            }

            // Filter tanggal (format: YYYY-MM-DD)
            if (dateValue && tanggal !== dateValue) {
                show = false;
            }

            row.style.display = show ? "" : "none";
        });
    }

    // Event listeners
    searchInput?.addEventListener("input", filterTable);
    dateInput?.addEventListener("change", filterTable);
    categoryInput?.addEventListener("change", filterTable);
});
</script>

@endsection