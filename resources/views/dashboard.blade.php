@extends('layouts.app')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- Info Cards -->
<div class="row">

    <!-- Barang Masuk -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Barang Masuk
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">1,240</div>
                <small class="text-muted">Total bulan ini</small>
            </div>
        </div>
    </div>

    <!-- Barang Keluar -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    Barang Keluar
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">980</div>
                <small class="text-muted">Total bulan ini</small>
            </div>
        </div>
    </div>

    <!-- Barang Return -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Barang Return
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">42</div>
                <small class="text-muted">Total bulan ini</small>
            </div>
        </div>
    </div>

    <!-- Stok Akhir -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Stok Akhir
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">5,920</div>
                <small class="text-muted">Keseluruhan</small>
            </div>
        </div>
    </div>

</div>

<!-- Chart -->
<div class="row">

    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Regular Sell</h6>
                <button class="btn btn-sm btn-success">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
            <div class="card-body">
                <canvas id="regularSellChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Analysis -->
    <div class="col-lg-4 mb-4">

        <div class="card shadow mb-3">
            <div class="card-body">
                <h6 class="font-weight-bold">Store Sell Ratio</h6>
                <p class="text-muted mb-0">Analisis berdasarkan toko</p>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <h6 class="font-weight-bold">Top Item Sold</h6>
                <p class="text-muted mb-0">Barang paling laris</p>
            </div>
        </div>

    </div>

</div>

<!-- Daily Meeting -->
<div class="card shadow mb-4">
    <div class="card-body">
        <h5 class="font-weight-bold">Daily Meeting</h5>
        <p class="text-muted">12 person • 08:30 PM</p>
        <a href="#" class="btn btn-dark btn-sm">
            <i class="fas fa-video"></i> Meeting Link
        </a>
    </div>
</div>

<!-- ChartJS -->
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
                backgroundColor: '#4e73df'
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

@endsection
