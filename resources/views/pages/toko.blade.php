@extends('layouts.app')

@section('content')
<div class="px-6 py-4">

    <h1 class="text-3xl font-bold mb-6">Kelola Toko</h1>

    <!-- Tombol Tambah Toko -->
    <div class="mb-4">
        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
            + Tambah Toko
        </button>
    </div>

    <!-- Card info singkat (dummy) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-100 p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Total Toko</h2>
            <p class="text-2xl font-bold">3</p>
        </div>
        <div class="bg-yellow-100 p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Toko Aktif</h2>
            <p class="text-2xl font-bold">3</p>
        </div>
        <div class="bg-red-100 p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Toko Nonaktif</h2>
            <p class="text-2xl font-bold">0</p>
        </div>
    </div>

    <!-- Table daftar toko (dummy) -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Toko</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $tokos = [
                        ['nama' => 'Lazz 1', 'alamat' => 'Jl. Melati No.10, Batubulan', 'status' => 'aktif'],
                        ['nama' => 'Lazz 2', 'alamat' => 'Jl. Kenanga No.5, Klungkung', 'status' => 'aktif'],
                        ['nama' => 'Nephew', 'alamat' => 'Jl. Anggrek No.7, Ubud', 'status' => 'aktif'],
                    ];
                @endphp

                @foreach ($tokos as $index => $toko)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $toko['nama'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $toko['alamat'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $toko['status'] == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($toko['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="text-blue-600 hover:underline mr-2">Edit</button>
                        <button class="text-red-600 hover:underline">Hapus</button>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</div>
@endsection
