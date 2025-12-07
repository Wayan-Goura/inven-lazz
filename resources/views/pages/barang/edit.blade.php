@extends('layouts.app')

@section('content')
<div class="px-6 py-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Barang</h1>

    <form action="{{ route('barang.update', $barang->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Kode Barang -->
        
        <!-- Nama Barang -->
        <div>
            <label for="nama_barang" class="block mb-1 font-medium">Nama Barang</label>
            <input 
            type="text" 
            id="nama_barang"
            name="nama_barang" 
                value="{{ old('nama_barang', $barang->nama_barang) }}" 
                class="w-full border p-2 rounded @error('nama_barang') border-red-500 @enderror"
                required
            >
            @error('nama_barang')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        
        <div>
            <label for="k_barang" class="block mb-1 font-medium">Kode Barang</label>
            <input 
                type="text" 
                id="k_barang"
                name="k_barang" 
                value="{{ old('k_barang', $barang->k_barang) }}" 
                class="w-full border p-2 rounded @error('k_barang') border-red-500 @enderror"
                required
            >
            @error('k_barang')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <!-- Kategori -->
        <div>
            <label for="category_id" class="block mb-1 font-medium">Kategori</label>
            <select 
                id="category_id"
                name="category_id" 
                class="w-full border p-2 rounded @error('category_id') border-red-500 @enderror"
                required
            >
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ old('category_id', $barang->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->nama_category }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Stok -->
        <div>
            <label for="jml_stok" class="block mb-1 font-medium">Jumlah Stok</label>
            <input 
                type="number" 
                id="jml_stok"
                name="jml_stok" 
                value="{{ old('jml_stok', $barang->jml_stok) }}" 
                min="0"
                class="w-full border p-2 rounded @error('jml_stok') border-red-500 @enderror"
                required
            >
            @error('jml_stok')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tombol -->
        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('barang.index') }}" 
               class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Update Barang
            </button>
        </div>

    </form>
</div>
@endsection
