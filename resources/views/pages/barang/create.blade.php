@extends('layouts.app')

@section('content')
<div class="px-6 py-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Tambah Barang</h1>

    <form action="{{ route('barang.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
        @csrf

        <div>
            <label class="block mb-1 font-medium">Nama Barang</label>
            <input type="text" name="nama_barang" placeholder="Bolpoin Hitam" class="w-full border p-2 rounded" required>
            @error('nama_barang')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Kategori</label>
            <select name="category_id" class="w-full border p-2 rounded" required>
                <option value="">Pilih Kategori</option>
                @foreach(\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->nama_category }}</option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Kode Barang</label>
            <input type="text" name="k_barang" placeholder="BRG001" class="w-full border p-2 rounded" required>
            @error('k_barang')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Jumlah Stok</label>
            <input type="number" name="jml_stok" placeholder="120" class="w-full border p-2 rounded" min="0" required>
            @error('jml_stok')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
