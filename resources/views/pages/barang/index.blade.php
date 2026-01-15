@extends('layouts.app')

@push('styles')
<style>
    .table-scroll {
        max-height: 410px; /* aman untuk laptop & desktop */
        overflow-y: auto;
    }

    .table-scroll thead th {
        position: sticky;
        top: 0;
        background: #f8f9fc;
        z-index: 2;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-box mr-2"></i> Data Barang
        </h1>
    </div>

    <div class="card shadow mb-4">

        <div class="card-header py-3 d-flex flex-wrap align-items-center">
            <div>
                <a href="{{ route('barang.create') }}" class="btn btn-sm btn-success mr-2">
                    <i class="fas fa-plus mr-1"></i> Tambah Barang
                </a>
                <a href="{{ route('barang.cetak_pdf') }}" target="_blank" class="btn btn-sm btn-primary">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
            </div>

            <div class="ml-auto d-flex flex-wrap align-items-center">
                <form method="GET" class="ml-auto d-flex flex-wrap align-items-center">
                <input type="text"
           name="search"
           value="{{ request('search') }}"
           class="form-control form-control-sm mr-2"
           style="width:180px"
           placeholder="Cari barang...">

    <input type="date"
           name="date"
           value="{{ request('date') }}"
           class="form-control form-control-sm mr-2"
           style="width:160px">

    <select name="category_id"
            class="form-control form-control-sm mr-2"
            style="width:180px">
        <option value="">Semua Kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->nama_category }}
            </option>
        @endforeach
    </select>

    <button class="btn btn-sm btn-primary">
        Filter
    </button>

    <a href="{{ route('barang.index') }}"
       class="btn btn-sm btn-secondary ml-2">
        Reset
    </a>
    </form>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive table-scroll">
                <table class="table table-bordered table-hover" id="barangTable">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="50">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Merek</th>
                            <th>Kategori</th>
                            <th width="120">Stok</th>
                            <th width="140">Tanggal</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataBarangs as $index => $barang)
                        <tr>
                            <td class="text-center">{{ $dataBarangs->firstItem() + $index }}</td>
                            <td><code>{{ $barang->k_barang }}</code></td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->merek }}</td>
                            <td data-category-id="{{ $barang->category_id ?? '' }}">
                                {{ $barang->category->nama_category ?? '—' }}
                            </td>
                            <td class="text-center">
                                @if($barang->jml_stok < 10)
                                    <span class="badge badge-danger">{{ $barang->jml_stok }}</span>
                                @elseif($barang->jml_stok <= 20)
                                    <span class="badge badge-warning">{{ $barang->jml_stok }}</span>
                                @else
                                    <span class="badge badge-success">{{ $barang->jml_stok }}</span>
                                @endif
                            </td>
                            <td class="text-center"
                                data-date="{{ $barang->created_at->toDateString() }}">
                                {{ $barang->created_at->format('d M Y') }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('barang.edit',$barang->id) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('barang.destroy',$barang->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada data ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <small class="text-muted mr-3">
                    @if ($dataBarangs->count() > 0)
                        Menampilkan {{ $dataBarangs->firstItem() ?? 1 }} –
                        {{ $dataBarangs->lastItem() ?? $dataBarangs->count() }}
                        dari {{ $dataBarangs->total() ?? $dataBarangs->count() }} data
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
            {{ $dataBarangs->withQueryString()->links() }}
        @endif
    </div>

</div>

    </div>
</div>
@endsection


