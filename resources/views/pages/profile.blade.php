@extends('layouts.app')

@section('content')
<div class="px-6 py-6 max-w-3xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">Profil Saya</h1>

    <!-- Card Profil -->
    <div class="bg-white shadow rounded-lg p-6 flex flex-col md:flex-row items-center gap-6">

        <!-- Foto Profil -->
        <div class="flex-shrink-0">
            <img class="w-32 h-32 rounded-full border-2 border-gray-200"
                 src="{{ asset('images/Lazz.jpeg') }}" 
                 alt="Foto Profil">
        </div>

        <!-- Info Profil -->
        <div class="flex-1">
            <h2 class="text-2xl font-semibold mb-2">Gudang Lazz Helmet</h2>
            <p class="text-gray-600 mb-1"><strong>Email:</strong> example@email.com</p>
            <p class="text-gray-600 mb-1"><strong>Telepon:</strong> +62 812-3456-7890</p>
            <p class="text-gray-600 mb-1"><strong>Alamat:</strong> Jl. Raya Ubud No.12, Bali</p>
            
            <!-- Tombol Edit Profil -->
            <div class="mt-4">
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Edit Profil
                </button>
            </div>
        </div>

    </div>

    <!-- Section Aktivitas / Info Tambahan (opsional) -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-3">Info Tambahan</h3>
        <div class="bg-gray-50 p-4 rounded shadow">
            <p class="text-gray-600">Belum ada info tambahan.</p>
        </div>
    </div>

</div>
@endsection
