@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <div class="mb-3">
        <a href="{{ route('persetujuan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @php
        $pending = is_array($item->pending_perubahan) ? $item->pending_perubahan : [];
        $isDelete = $pending['is_delete'] ?? false;
        $fields = array_diff_key($pending, ['is_delete' => '']);
        $totalFields = count($fields) > 0 ? count($fields) : 1;

        $detail = in_array($type, ['masuk', 'keluar']) 
            ? optional($item->detailTransaksis)->first() 
            : null;
    @endphp

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="text-center bg-light text-muted">
                        <tr>
                            <th width="20%">Atribut</th>
                            <th>Data Lama</th>
                            @if(!$isDelete)
                                <th>Data Baru</th>
                            @endif
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- Baris Identitas Barang/Transaksi --}}
                        <tr class="bg-light">
                            <td colspan="{{ $isDelete ? 3 : 4 }}" class="ps-3">
                                <strong>
                                    @if ($type === 'barang')
                                        {{ $item->k_barang }} - {{ $item->nama_barang }}
                                    @elseif (in_array($type, ['masuk', 'keluar']))
                                        {{ $item->kode_transaksi }}
                                    @elseif ($type === 'return')
                                        RT-{{ optional($item->dataBarang)->k_barang }}
                                    @endif
                                </strong>
                                <span class="badge {{ $isDelete ? 'bg-danger' : 'bg-primary' }} ms-2">
                                    {{ $isDelete ? 'HAPUS' : 'EDIT' }}
                                </span>
                            </td>
                        </tr>

                        @forelse($fields as $field => $newValue)
                            <tr>
                                <td class="text-capitalize ps-3 text-muted">
                                    {{ str_replace('_', ' ', $field) }}
                                </td>

                                {{-- DATA LAMA (NAMA BARANG, BUKAN ID) --}}
                                <td class="ps-3 text-muted">
                                    @php
                                        if (in_array($type, ['masuk', 'keluar']) && $detail) {
                                            $oldValue = match ($field) {
                                                'jumlah' => $detail->jumlah ?? '-',
                                                'data_barang_id' => optional($detail->dataBarang)->nama_barang ?? '-',
                                                'tanggal_transaksi' => $item->tanggal_transaksi ?? '-',
                                                'lokasi' => $item->lokasi ?? '-',
                                                default => $item->$field ?? '-',
                                            };
                                        } else {
                                            if ($field === 'data_barang_id') {
                                                $oldValue = optional($item->dataBarang)->nama_barang ?? '-';
                                            } else {
                                                $oldValue = $item->$field ?? '-';
                                            }
                                        }
                                    @endphp
                                    {{ $oldValue }}
                                </td>

                                {{-- DATA BARU (NAMA BARANG, BUKAN ID) --}}
                                @if(!$isDelete)
                                    <td class="ps-3 fw-bold text-success">
                                        @php
                                            if ($field === 'data_barang_id') {
                                                // Mencari nama barang baru berdasarkan ID di database
                                                $barangBaru = \App\Models\DataBarang::find($newValue);
                                                $displayValue = $barangBaru ? $barangBaru->nama_barang : $newValue;
                                            } else {
                                                $displayValue = $newValue;
                                            }
                                        @endphp
                                        {{ $displayValue }}
                                    </td>
                                @endif

                                {{-- AKSI --}}
                                @if($loop->first)
                                    <td rowspan="{{ $totalFields }}" class="text-center">
                                        <form id="form-detail-{{ $item->id }}" 
                                              action="{{ route('persetujuan.proses', ['type' => $type, 'id' => $item->id]) }}" 
                                              method="POST">
                                            @csrf
                                            <input type="hidden" name="action" id="action-detail-{{ $item->id }}">
                                            
                                            <button type="button" class="btn btn-success btn-sm px-3" 
                                                    onclick="detailAction('{{ $item->id }}','setuju')">
                                                Setuju
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm px-3" 
                                                    onclick="detailAction('{{ $item->id }}','tolak')">
                                                Tolak
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            {{-- Tampilan jika Hapus Data --}}
                            <tr>
                                <td colspan="{{ $isDelete ? 2 : 3 }}" class="ps-3 text-center text-muted py-3">
                                    Seluruh data ini diajukan untuk dihapus.
                                </td>
                                <td class="text-center">
                                    <form id="form-detail-{{ $item->id }}" action="{{ route('persetujuan.proses', ['type' => $type, 'id' => $item->id]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" id="action-detail-{{ $item->id }}">
                                        <button type="button" class="btn btn-success btn-sm" onclick="detailAction('{{ $item->id }}','setuju')">Setuju</button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="detailAction('{{ $item->id }}','tolak')">Tolak</button>
                                    </form>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function detailAction(id, action) {
    Swal.fire({
        title: action === 'setuju' ? 'Setujui Perubahan?' : 'Tolak Perubahan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'setuju' ? '#198754' : '#dc3545',
        confirmButtonText: 'Ya, Lanjutkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('action-detail-' + id).value = action;
            document.getElementById('form-detail-' + id).submit();
        }
    });
}
</script>
@endsection