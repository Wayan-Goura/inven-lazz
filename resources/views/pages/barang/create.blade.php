@extends('layouts.app')

@section('content')
<div class="px-6 py-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Tambah Barang (Dummy)</h1>

    <form class="bg-white p-6 rounded shadow space-y-4">

        <div>
            <label class="block mb-1 font-medium">Kode Barang</label>
            <input type="text" name="kode" placeholder="BRG001" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Nama Barang</label>
            <input type="text" name="nama" placeholder="Bolpoin Hitam" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Merk</label>
            <input type="text" name="merk" placeholder="Standard" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Harga Beli</label>
            <input type="number" name="harga_beli" placeholder="2000" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Harga Jual</label>
            <input type="number" name="harga_jual" placeholder="3000" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Stok</label>
            <input type="number" name="stok" placeholder="120" class="w-full border p-2 rounded">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ url('/barang') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</a>
            <button type="button" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
