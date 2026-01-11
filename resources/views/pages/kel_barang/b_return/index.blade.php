@extends('layouts.app')

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

    <div class="card shadow mb-4">
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
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
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

                        {{-- DATA ASLI (TIDAK BERUBAH SAAT PENDING) --}}
                        <td>{{ $return->getRawOriginal('tanggal_return')}}</td>
                        <td class="text-center">{{ $return->getRawOriginal('jumlah_return') }}</td>
                        <td>{{ $return->getRawOriginal('deskripsi') }}</td>

                        <td class="text-center">
                            @if($return->is_disetujui)
                                <button class="btn btn-sm btn-warning shadow-sm" 
                                        title="Menunggu persetujuan" disabled>
                                    <i class="fas fa-clock"></i>
                                </button>
                            @else
                                <a href="{{ route('kel_barang.b_return.edit', $return->id) }}"
                                   class="btn btn-sm btn-success shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif

                            <form id="delete-form-{{ $return->id }}" 
                                  action="{{ route('kel_barang.b_return.destroy', $return->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmDelete('{{ $return->id }}', '{{ $return->dataBarang->nama_barang }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Data belum tersedia
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
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
