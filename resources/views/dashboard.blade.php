@extends('layouts.app')

@section('content')

<div class="px-6 py-4">

    <h1 class="text-2xl font-bold mb-2">Dashboard</h1>
    <p class="text-gray-600 mb-6">Selamat datang di sistem stok barang.</p>

    <!-- BOX ATAS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <!-- Barang Masuk -->
        <div class="p-5 rounded-xl bg-purple-200 shadow">
            <h3 class="text-lg font-semibold">Barang Masuk</h3>
            <p class="text-3xl font-bold mt-2">1,240</p>
            <p class="text-sm text-gray-700 mt-1">Total bulan ini</p>
        </div>

        <!-- Barang Keluar -->
        <div class="p-5 rounded-xl bg-blue-200 shadow">
            <h3 class="text-lg font-semibold">Barang Keluar</h3>
            <p class="text-3xl font-bold mt-2">980</p>
            <p class="text-sm text-gray-700 mt-1">Total bulan ini</p>
        </div>

        <!-- Barang Return -->
        <div class="p-5 rounded-xl bg-green-200 shadow">
            <h3 class="text-lg font-semibold">Barang Return</h3>
            <p class="text-3xl font-bold mt-2">42</p>
            <p class="text-sm text-gray-700 mt-1">Total bulan ini</p>
        </div>

        <!-- Stok Akhir -->
        <div class="p-5 rounded-xl bg-yellow-200 shadow">
            <h3 class="text-lg font-semibold">Stok Akhir</h3>
            <p class="text-3xl font-bold mt-2">5,920</p>
            <p class="text-sm text-gray-700 mt-1">Keseluruhan</p>
        </div>

    </div>

    <!-- REGULAR SELL CHART -->
    <div class="mt-8 bg-white p-6 rounded-xl shadow">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Regular Sell</h2>
            <button class="px-3 py-1 bg-green-500 text-white text-sm rounded-lg">Export</button>
        </div>
        <canvas id="regularSellChart" class="mt-4"></canvas>
    </div>

    <!-- MORE ANALYSIS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">

        <!-- Store Sell Ratio -->
        <div class="p-5 bg-white shadow rounded-xl flex justify-between items-center">
            <div>
                <h4 class="text-lg font-semibold">Store Sell Ratio</h4>
                <p class="text-sm text-gray-500">Analisis berdasarkan toko</p>
            </div>
            <span class="text-gray-400 text-xl">&gt;</span>
        </div>

        <!-- Top Item Sold -->
        <div class="p-5 bg-white shadow rounded-xl flex justify-between items-center">
            <div>
                <h4 class="text-lg font-semibold">Top Item Sold</h4>
                <p class="text-sm text-gray-500">Barang paling laris</p>
            </div>
            <span class="text-gray-400 text-xl">&gt;</span>
        </div>

    </div>

    <!-- DAILY MEETING -->
    <div class="mt-6 bg-white shadow p-6 rounded-xl">
        <h3 class="text-lg font-bold">Daily Meeting</h3>
        <p class="text-sm text-gray-500">12 person • 08:30 PM</p>
        <button class="mt-3 px-4 py-2 bg-black text-white rounded-lg">
            Click for meeting link
        </button>
    </div>

</div>

<!-- ChartJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('regularSellChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'Penjualan',
                data: [10, 22, 45, 30, 18, 40, 35],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
