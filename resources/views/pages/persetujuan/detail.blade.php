@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <div class="mb-3">
        <a href="{{ route('persetujuan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @php
        // Pastikan variabel fields didefinisikan di atas sebelum count() dipanggil
        $isDelete = isset($item->pending_perubahan['is_delete']) && $item->pending_perubahan['is_delete'];
        $fields = is_array($item->pending_perubahan) ? array_diff_key($item->pending_perubahan, ['is_delete' => '']) : [];
        $totalFields = count($fields) > 0 ? count($fields) : 1; 
    @endphp

    <div class="table-responsive bg-white shadow-sm">
        <table class="table table-bordered mb-0">
            <thead class="text-center text-muted">
                <tr>
                    <th>Atribut</th>
                    <th>Data Lama</th>
                    @if(!$isDelete)
                        <th>Data Baru</th>
                    @endif
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Header Abu-abu --}}
                <tr class="table-secondary">
                    <td colspan="{{ $isDelete ? 3 : 4 }}">
                        <strong class="text-muted">
                            @if($type == 'barang')
                                Barang: {{ $item->nama_barang }} ({{ $item->k_barang }})
                            @else
                                Transaksi: {{ optional($item->dataBarang)->nama_barang ?? 'N/A' }} (ID: #{{ $item->id }})
                            @endif
                            <span class="ms-2 badge {{ $isDelete ? 'bg-danger' : 'bg-primary' }}">
                                {{ $isDelete ? 'HAPUS' : 'EDIT' }}
                            </span>
                        </strong>
                    </td>
                </tr>

                @forelse ($fields as $field => $newValue)
                    <tr>
                        <td class="text-muted ps-3 text-capitalize">{{ str_replace('_', ' ', $field) }}</td>
                        <td class="ps-3">{{ $item->$field ?? '-' }}</td>
                        
                        @if(!$isDelete)
                            <td class="ps-3 text-success fw-bold">{{ $newValue }}</td>
                        @endif

                        @if ($loop->first)
                            <td rowspan="{{ $totalFields }}" class="align-middle text-center">
                                <form id="form-detail-{{ $item->id }}" action="{{ route('persetujuan.proses', ['type' => $type, 'id' => $item->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" id="action-detail">
                                    <button type="button" class="btn btn-success btn-sm w-100 mb-2 py-2 fw-bold" onclick="detailAction('setuju')">
                                        <i class="fas fa-check"></i> Setuju
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm w-100 py-2 fw-bold" onclick="detailAction('tolak')">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    {{-- Kasus jika tidak ada perubahan data atau is_delete tanpa field lain --}}
                    <tr>
                        <td colspan="{{ $isDelete ? 2 : 3 }}" class="text-center text-muted">Data ini diajukan untuk {{ $isDelete ? 'dihapus' : 'diubah' }}.</td>
                        <td class="align-middle text-center">
                             <form id="form-detail-{{ $item->id }}" action="{{ route('persetujuan.proses', ['type' => $type, 'id' => $item->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" id="action-detail">
                                <button type="button" class="btn btn-success btn-sm w-100 mb-2 py-2 fw-bold" onclick="detailAction('setuju')">Setuju</button>
                                <button type="button" class="btn btn-danger btn-sm w-100 py-2 fw-bold" onclick="detailAction('tolak')">Tolak</button>
                            </form>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function detailAction(action) {
    Swal.fire({
        title: action === 'setuju' ? 'Konfirmasi Setuju' : 'Konfirmasi Tolak',
        text: "Apakah Anda yakin ingin memproses data ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'setuju' ? '#198754' : '#dc3545',
        confirmButtonText: action === 'setuju' ? 'Ya, Setuju!' : 'Ya, Tolak!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('action-detail').value = action;
            document.getElementById('form-detail-{{ $item->id }}').submit();
        }
    })
}
</script>
@endsection