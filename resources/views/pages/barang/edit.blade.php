@extends('layouts.app')

@section('content')
<div class="px-6 py-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Barang (Dummy)</h1>

    <form class="bg-white p-6 rounded shadow space-y-4">

        <div>
            <label class="block mb-1 font-medium">Kode Barang</label>
            <input type="text" name="kode" value="BRG001" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Nama Barang</label>
            <input type="text" name="nama" value="Bolpoin Hitam" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Merk</label>
            <input type="text" name="merk" value="Standard" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Harga Beli</label>
            <input type="number" name="harga_beli" value="2000" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Harga Jual</label>
            <input type="number" name="harga_jual" value="3000" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Stok</label>
            <input type="number" name="stok" value="120" class="w-full border p-2 rounded">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ url('/barang') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</a>
            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection
