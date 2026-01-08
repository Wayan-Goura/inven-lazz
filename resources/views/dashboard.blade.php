@extends('layouts.app')

@section('content')
<div class="container-fluid" style="overflow-y: auto; max-height: 90vh; padding-bottom: 50px;">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">No of users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUser ?? 0 }}</div>
                            <div class="text-xs text-gray-500 mt-1">Total Customers</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills mb-3 shadow-sm p-2 bg-white rounded" id="pills-tab" role="tablist">
        <li class="nav-item"><a class="nav-link active" id="pills-hari-tab" data-toggle="pill" href="#pills-hari">Hari Ini</a></li>
        <li class="nav-item"><a class="nav-link" id="pills-bulan-tab" data-toggle="pill" href="#pills-bulan">Bulan Ini</a></li>
        <li class="nav-item"><a class="nav-link" id="pills-tahun-tab" data-toggle="pill" href="#pills-tahun">Tahun Ini</a></li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-hari" role="tabpanel">
            <div class="row">
                @include('partials.dashboard-cards', ['label' => 'Hari Ini', 'masuk' => $masukHariIni, 'keluar' => $keluarHariIni, 'return' => $returnHariIni, 'labelStok' => 'Total Jenis Barang', 'stokValue' => $totalDataBarang])
            </div>
        </div>
        <div class="tab-pane fade" id="pills-bulan" role="tabpanel">
            <div class="row">
                @include('partials.dashboard-cards', ['label' => 'Bulan Ini', 'masuk' => $barangMasuk, 'keluar' => $barangKeluar, 'return' => $barangReturn, 'labelStok' => 'Stok Akhir', 'stokValue' => $stokAkhir])
            </div>
        </div>
        <div class="tab-pane fade" id="pills-tahun" role="tabpanel">
            <div class="row">
                @include('partials.dashboard-cards', ['label' => 'Tahun Ini', 'masuk' => $masukTahunIni, 'keluar' => $keluarTahunIni, 'return' => $returnTahunIni, 'labelStok' => 'Stok Akhir', 'stokValue' => $stokAkhir])
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Inventory Values</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="inventoryPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Total Units</span>
                        <span class="mr-2"><i class="fas fa-circle text-info"></i> Sold Units</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Expense vs Profit (Last 6 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="comparisonLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Tren Barang Keluar (7 Hari Terakhir)</h6></div>
                <div class="card-body">
                    <div class="chart-area" style="position: relative; height: 300px; overflow-x: auto;">
                        <div style="min-width: 600px; height: 100%;"><canvas id="regularSellChart"></canvas></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-danger">Stok Menipis</h6></div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @forelse($stokMenipis ?? [] as $item)
                    <div class="mb-3">
                        <div class="small text-gray-500">
                            @if($item->stok < 10) <i class="fas fa-exclamation-triangle text-warning mr-1"></i> @endif
                            {{ $item->nama_barang }}
                            <span class="float-right"><b class="{{ $item->stok < 10 ? 'text-danger' : '' }}">{{ $item->stok }} unit</b></span>
                        </div>
                        <div class="progress progress-sm mb-2"><div class="progress-bar bg-danger" style="width: 25%"></div></div>
                    </div>
                    @empty
                    <p class="text-center text-muted">Semua stok aman.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6></div>
        <div class="card-body">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light" style="position: sticky; top: 0; z-index: 1;">
                        <tr><th>Tanggal</th><th>Barang</th><th>Tipe</th><th>Jumlah</th><th>User</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities ?? [] as $activity)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y H:i') }}</td>
                            <td>{{ $activity->nama_barang }}</td>
                            <td><span class="badge {{ $activity->type == 'masuk' ? 'badge-primary' : 'badge-info' }}">{{ ucfirst($activity->type) }}</span></td>
                            <td>{{ $activity->qty }}</td>
                            <td>{{ $activity->user_name }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">Belum ada aktivitas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Chart Batang (Tren 7 Hari)
    const ctx = document.getElementById('regularSellChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData->pluck('hari')) !!},
            datasets: [{ label: 'Barang Keluar', data: {!! json_encode($chartData->pluck('total')) !!}, backgroundColor: '#4e73df', borderRadius: 5 }]
        },
        options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });

    // 2. Pie Chart (Inventory Value)
    const ctxPie = document.getElementById('inventoryPieChart');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ["Total Units", "Sold Units"],
            datasets: [{
                data: [{{ $stokAkhir ?? 0 }}, {{ $barangKeluar ?? 0 }}],
                backgroundColor: ['#4e73df', '#a2c1e8'],
                hoverBackgroundColor: ['#2e59d9', '#85aadd'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: { maintainAspectRatio: false, cutout: '70%'}
    });

    // 3. Line Chart (Comparison 6 Months)
    const ctxLine = document.getElementById('comparisonLineChart');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($sixMonthsData->pluck('bulan')) !!},
            datasets: [
                { label: 'Masuk (Expense)', data: {!! json_encode($sixMonthsData->pluck('masuk')) !!}, borderColor: '#1cc88a', fill: true, backgroundColor: 'rgba(28, 200, 138, 0.1)', tension: 0.4 },
                { label: 'Keluar (Profit)', data: {!! json_encode($sixMonthsData->pluck('keluar')) !!}, borderColor: '#4e73df', fill: true, backgroundColor: 'rgba(78, 115, 223, 0.1)', tension: 0.4 }
            ]
        },
        options: { maintainAspectRatio: false }
    });
</script>
@endsection