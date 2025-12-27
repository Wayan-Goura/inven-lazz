@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Barang Return</h1>

    <!-- TOP BAR -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">

            <!-- KIRI -->
            <div>
                <a href="{{ route('kel_barang.b_return.create') }}"
                   class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>

                <a href="#" class="btn btn-sm btn-secondary">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>

            <!-- KANAN -->
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm"
                       placeholder="Cari barang..." data-search>
            </div>

        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Merk</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Alasan</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>

                <tbody>
                @forelse($barangs as $i => $barang)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $barang->kode }}</td>
                    <td>{{ $barang->nama }}</td>
                    <td>{{ $barang->merk }}</td>
                    <td>{{ $barang->kategori }}</td>
                    <td>{{ $barang->tanggal }}</td>
                    <td>{{ $barang->alasan }}</td>
                    <td class="text-center">{{ $barang->jumlah }}</td>

                    <td class="text-center">
                        <a href="{{ route('kel_barang.b_return.edit', $barang->id) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('kel_barang.b_return.destroy', $barang->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        Data belum tersedia
                    </td>
                </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection
