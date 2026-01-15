@extends('layouts.app')


<style>
    .table-scroll-area {
        position: relative;
        max-height: 410px; 
        overflow-y: auto; 
        overflow-x: auto; 
        border: 1px solid #e3e6f0;
    }

    .table-scroll-area table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-scroll-area table thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fc !important;
        z-index: 10;
        box-shadow: inset 0 -1px 0 #e3e6f0;
        border-top: 0;
    }
</style>

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Barang Return</h1>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        </script>
    @endif

    <div class="card shadow mb-2">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">

            <div>
                <a href="{{ route('kel_barang.b_return.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>

                <a href="{{ route('kel_barang.b_return.cetak_return_pdf') }}" 
                target="_blank" 
                class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>

            <div>
                <input type="text" class="form-control form-control-sm"
                       placeholder="Cari barang..." data-search>
            </div>

        </div>
    </div>

    <div class="card shadow">
    <div class="card-body">
        
        <div class="table-scroll-area">
            <table class="table table-bordered table-hover mb-0"> <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Tanggal Return</th>
                        <th>Jumlah Return</th>
                        <th>Alasan</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($barangReturn as $i => $return)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $return->dataBarang->nama_barang }}</td>
                            <td>{{ $return->category->nama_category }}</td>
                            <td>{{ $return->getRawOriginal('tanggal_return')}}</td>
                            <td class="text-center">{{ $return->getRawOriginal('jumlah_return') }}</td>
                            <td>{{ $return->getRawOriginal('deskripsi') }}</td>
                            <td class="text-center">
                                @if($return->is_disetujui)
                                    <button class="btn btn-sm btn-warning shadow-sm" title="Menunggu persetujuan" disabled>
                                        <i class="fas fa-clock"></i>
                                    </button>
                                @else
                                    <a href="{{ route('kel_barang.b_return.edit', $return->id) }}" class="btn btn-sm btn-success shadow-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif

                                <form id="delete-form-{{ $return->id }}" action="{{ route('kel_barang.b_return.destroy', $return->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $return->id }}', '{{ $return->dataBarang->nama_barang }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Data belum tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <small class="text-muted mr-3">
                    @if ($barangReturn->count() > 0)
                        Menampilkan {{ $barangReturn->firstItem() ?? 1 }} –
                        {{ $barangReturn->lastItem() ?? $barangReturn->count() }}
                        dari {{ $barangReturn->total() ?? $barangReturn->count() }} data
                    @else
                        Tidak ada data
                    @endif
                </small>

                {{-- SHOW PER PAGE --}}
                <form method="GET">
                    <select name="per_page"
                            class="form-control form-control-sm"
                            onchange="this.form.submit()">

                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>
                            Semua
                        </option>
                    </select>
                </form>
            </div>
    {{-- PAGINATION --}}
    <div>
        @if(request('per_page') !== 'all')
            {{ $barangReturn->withQueryString()->links() }}
        @endif
    </div>

    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Konfirmasi Hapus
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Hapus Data Return?',
        text: "Menghapus data return barang: " + name,
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

// Fitur Search
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("[data-search]");
    const tableRows = document.querySelectorAll("tbody tr");

    searchInput.addEventListener("input", function() {
        const searchValue = this.value.toLowerCase();
        tableRows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(searchValue)
                ? ""
                : "none";
        });
    });
});
</script>
@endsection
