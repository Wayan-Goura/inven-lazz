@extends('layouts.app')

@section('content')

<style>
    /* 1. Kunci container utama agar tidak ikut melar */
    .container-fluid {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 100px); /* Menyesuaikan tinggi layar dikurangi navbar */
    }

    /* 2. Styling khusus untuk area scroll tabel */
    .table-scroll-area {
        flex: 1;
        overflow-y: auto; /* Paksa muncul scrollbar vertikal */
        overflow-x: auto; /* Scroll horizontal jika layar sempit */
        border: 1px solid #e3e6f0;
        position: relative;
        max-height: 65vh; /* Mengunci tinggi tabel maksimal 65% dari tinggi layar */
    }

    /* 3. Membuat Header tetap diam di atas */
    .table-scroll-area table thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fc !important;
        z-index: 99;
        box-shadow: inset 0 -1px 0 #e3e6f0;
    }

    /* Menghilangkan margin bawah card agar tidak double scroll */
    .card-body {
        padding: 1rem;
    }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Barang Masuk</h1>
    
    {{-- Bagian Success Alert Lama Dihapus, diganti ke Script di bawah --}}

    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">
            <div>
                <a href="{{ route('transaksi.cetak_masuk_pdf') }}"
                   target="_blank"
                   class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
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
                    <option value="Shopee">Shopee</option>
                    <option value="Tiktok Shop">Tiktok Shop</option>
                    <option value="Toko Nephew">Toko Nephew</option>
                    <option value="Toko Batubulan">Toko Batubulan</option>
                    <option value="Toko Klungkung">Toko Klungkung</option>
                    <option value="Gudang Klungkung">Gudang Klungkung</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-scroll-area">
                <table class="table table-bordered table-hover mb-0">
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
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->kode_transaksi }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d-m-Y') }}</td>
                                <td>{{ $detail->barang->nama_barang ?? 'Barang Terhapus' }}</td>
                                <td>{{ $detail->barang->merek ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success">+ {{ $detail->jumlah }}</span>
                                </td>
                                <td>{{ $detail->barang->jml_stok ?? '0' }}</td>
                                <td>{{ $item->lokasi ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('transaksi.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('transaksi.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $item->id }}', '{{ $item->kode_transaksi }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(method_exists($transaksis, 'links'))
                <div class="mt-3">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- SCRIPT SWEETALERT & FILTER --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. TRIGGER SWEETALERT SUCCESS SAAT HALAMAN DIBUKA
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // 2. FUNGSI SWEETALERT UNTUK KONFIRMASI HAPUS
    function confirmDelete(id, kode) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data transaksi " + kode + " akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // 3. FUNGSI FILTER TABEL ASLI
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
                const tanggalText = row.children[2]?.innerText ?? ""; 
                const dateParts = tanggalText.split("-");
                const rowTanggal = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);

                let show = true;
                if (searchValue && !rowText.includes(searchValue)) show = false;
                if (tanggalValue === "today" && rowTanggal.toDateString() !== today.toDateString()) show = false;
                if (tanggalValue === "week" && rowTanggal < startOfWeek) show = false;
                if (tanggalValue === "month" && rowTanggal < startOfMonth) show = false;
                if (extraValue && !rowText.includes(extraValue)) show = false;

                row.style.display = show ? "" : "none";
            });
        }

        searchInput.addEventListener("input", filterTable);
        filterTanggal.addEventListener("change", filterTable);
        filterExtra.addEventListener("change", filterTable);
    });
</script>

@endsection