@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="tab-content mt-3">
        @foreach (['barang', 'masuk', 'keluar', 'return'] as $type)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $type }}">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>Atribut</th>
                                <th>Data Lama</th>
                                <th>Data Baru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- FIX: Cek apakah data[type] tersedia dan bisa di-loop --}}
                            @if(isset($data[$type]) && count($data[$type]) > 0)
                                @foreach ($data[$type] as $item)
                                    <tr class="table-secondary">
                                        <td colspan="4">
                                            <strong>
                                                @if($type == 'barang')
                                                    Barang: {{ $item->nama_barang }} ({{ $item->k_barang }})
                                                @else
                                                    {{-- FIX: Gunakan optional() agar tidak error jika relasi kosong --}}
                                                    Transaksi: {{ optional($item->dataBarang)->nama_barang ?? 'N/A' }} (ID: #{{ $item->id }})
                                                @endif
                                            </strong>
                                        </td>
                                    </tr>

                                    {{-- FIX: Cek apakah pending_perubahan adalah array sebelum di-loop --}}
                                    @if(is_array($item->pending_perubahan))
                                        @foreach ($item->pending_perubahan as $field => $newValue)
                                            <tr>
                                                <td class="text-capitalize">{{ str_replace('_', ' ', $field) }}</td>
                                                <td>{{ $item->$field ?? '-' }}</td>
                                                <td class="text-success fw-bold">{{ $newValue }}</td>
                                                
                                                @if ($loop->first)
                                                    <td rowspan="{{ count($item->pending_perubahan) }}" class="align-middle text-center">
                                                        <form action="{{ route('persetujuan.proses', ['type' => $type, 'id' => $item->id]) }}" method="POST">
                                                            @csrf
                                                            <button name="action" value="setuju" class="btn btn-success btn-sm mb-2 w-100 shadow-sm">
                                                                <i class="fas fa-check"></i> Setuju
                                                            </button>
                                                            <button name="action" value="tolak" class="btn btn-danger btn-sm w-100 shadow-sm">
                                                                <i class="fas fa-times"></i> Tolak
                                                            </button>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Tidak ada permintaan persetujuan untuk kategori {{ $type }}.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection