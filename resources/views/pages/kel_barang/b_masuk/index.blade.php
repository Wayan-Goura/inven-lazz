@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Barang Masuk</h1>

    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">
            <div>
                <a href="#" class="btn btn-sm btn-secondary">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>

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

    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Nama Barang</th>
                    <th>Merek</th>
                    <th>Jumlah</th>
                    <th>Sisa Stok</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
    @php $no = $transaksis->firstItem(); @endphp
    @foreach ($transaksis as $item)
        @foreach ($item->detailTransaksis as $detail)
        <tr>
            {{-- 1. Nomor urut --}}
            <td>{{ $no++ }}</td>
            
            {{-- 2. Data dari Tabel Transaksi --}}
            <td>{{ $item->kode_transaksi }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d-m-Y') }}</td>
            
            {{-- 3. Data dari Tabel Barang (melalui Detail) --}}
            <td>{{ $detail->barang->nama_barang ?? 'Barang Terhapus' }}</td>
            <td>{{ $detail->barang->merek ?? '-' }}</td>
            
            {{-- 4. Jumlah Keluar --}}
            <td class="text-center">
                <span class="badge badge-success">+ {{ $detail->jumlah }}</span>
            </td>
            <td>{{ $detail->barang->jml_stok ?? '0' }}</td>
            
            {{-- 5. Lokasi Tujuan/Keluar --}}
            <td>{{ $item->lokasi ?? '-' }}</td>

            {{-- 6. Aksi --}}
            <td class="text-center">
                <a href="{{ route('transaksi.edit', $item->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data transaksi ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    @endforeach
</tbody>
            </table>
            
            {{-- Tambahkan pagination link jika perlu --}}
            @if(method_exists($transaksis, 'links'))
                <div class="mt-3">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
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
