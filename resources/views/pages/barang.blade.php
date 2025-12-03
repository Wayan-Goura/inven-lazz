@extends('layouts.app')

@section('content')

<div class="px-6 py-4">

    <h1 class="text-2xl font-bold mb-4">Data Barang</h1>

    <!-- Tombol Tambah -->
    <a href="#" class="px-4 py-2 bg-green-600 text-white rounded-lg mb-4 inline-block">
        + Tambah Barang
    </a>

    <!-- TABEL DATA BARANG -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Kode Barang</th>
                    <th class="p-3 border">Nama Barang</th>
                    <th class="p-3 border">Merk</th>
                    <th class="p-3 border">Harga Beli</th>
                    <th class="p-3 border">Harga Jual</th>
                    <th class="p-3 border">Stok</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <!-- BARIS 1 -->
                <tr>
                    <td class="p-3 border text-center">1</td>
                    <td class="p-3 border">BRG001</td>
                    <td class="p-3 border">Bolpoin Hitam</td>
                    <td class="p-3 border">Standard</td>
                    <td class="p-3 border">2.000</td>
                    <td class="p-3 border">3.000</td>
                    <td class="p-3 border text-center">120</td>
                    <td class="p-3 border text-center flex gap-2 justify-center">

                        <!-- EDIT -->
                        <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded">
                            Edit
                        </a>

                        <!-- HAPUS -->
                        <a href="#" class="px-3 py-1 bg-red-600 text-white rounded">
                            Hapus
                        </a>

                    </td>
                </tr>

                <!-- BARIS 2 -->
                <tr>
                    <td class="p-3 border text-center">2</td>
                    <td class="p-3 border">BRG002</td>
                    <td class="p-3 border">Buku Tulis</td>
                    <td class="p-3 border">Sidu</td>
                    <td class="p-3 border">4.500</td>
                    <td class="p-3 border">6.500</td>
                    <td class="p-3 border text-center">80</td>
                    <td class="p-3 border text-center flex gap-2 justify-center">
                        <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded">Edit</a>
                        <a href="#" class="px-3 py-1 bg-red-600 text-white rounded">Hapus</a>
                    </td>
                </tr>

                <!-- BARIS 3 -->
                <tr>
                    <td class="p-3 border text-center">3</td>
                    <td class="p-3 border">BRG003</td>
                    <td class="p-3 border">Spidol Whiteboard</td>
                    <td class="p-3 border">Kenko</td>
                    <td class="p-3 border">6.000</td>
                    <td class="p-3 border">9.000</td>
                    <td class="p-3 border text-center">45</td>
                    <td class="p-3 border text-center flex gap-2 justify-center">
                        <a href="#" class="px-3 py-1 bg-blue-600 text-white rounded">Edit</a>
                        <a href="#" class="px-3 py-1 bg-red-600 text-white rounded">Hapus</a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

@endsection
