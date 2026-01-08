@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    {{-- Pesan Sukses/Error Otomatis --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500
            });
        </script>
    @endif

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="fw-bold">Persetujuan Perubahan Data</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="50">No</th>
                            <th>Kode Barang</th>
                            <th>Detail</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach (['barang', 'masuk', 'keluar', 'return'] as $type)
                            @if(isset($data[$type]))
                                @foreach ($data[$type] as $item)
                                    <tr class="text-center">
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <strong>
                                                {{ ($type == 'barang') ? $item->k_barang : (optional($item->dataBarang)->k_barang ?? '-') }}
                                            </strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('persetujuan.detail', ['type' => $type, 'id' => $item->id]) }}" class="btn btn-info btn-sm text-white px-3">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                        <td>
                                            <form id="form-{{ $type }}-{{ $item->id }}" action="{{ route('persetujuan.proses', ['type' => $type, 'id' => $item->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" id="action-{{ $type }}-{{ $item->id }}">
                                                
                                                <button type="button" class="btn btn-success btn-sm" onclick="confirmAction('setuju', '{{ $type }}', '{{ $item->id }}')">Setuju</button>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmAction('tolak', '{{ $type }}', '{{ $item->id }}')">Tolak</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmAction(action, type, id) {
    const title = action === 'setuju' ? 'Setujui Perubahan?' : 'Tolak Perubahan?';
    const text = action === 'setuju' ? 'Data akan segera diperbarui sesuai pengajuan.' : 'Pengajuan perubahan akan dihapus.';
    const confirmButtonColor = action === 'setuju' ? '#198754' : '#dc3545';

    Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: action === 'setuju' ? 'Ya, Setuju!' : 'Ya, Tolak!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('action-' + type + '-' + id).value = action;
            document.getElementById('form-' + type + '-' + id).submit();
        }
    });
}
</script>
@endsection