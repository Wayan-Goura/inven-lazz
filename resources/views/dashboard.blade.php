@extends('layouts.app')

@section('content')

<h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Barang Masuk</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($barangMasuk) }}</div>
                <small class="text-muted">Bulan ini</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Barang Keluar</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($barangKeluar) }}</div>
                <small class="text-muted">Bulan ini</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Barang Return</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($barangReturn) }}</div>
                <small class="text-muted">Bulan ini</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stok Akhir</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stokAkhir) }}</div>
                <small class="text-muted">Total Keseluruhan</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tren Barang Keluar (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="position: relative; height: 300px; overflow-x: auto;">
                    <div style="min-width: 600px; height: 100%;">
                        <canvas id="regularSellChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
    @forelse($stokMenipis ?? [] as $item)
    <div class="mb-3">
        <div class="small text-gray-500">
            @if($item->stok < 10)
                <i class="fas fa-exclamation-triangle text-warning mr-1" title="Stok sangat rendah!"></i>
            @endif
            
            {{ $item->nama_barang }}
            
            <span class="float-right">
                <b class="{{ $item->stok < 10 ? 'text-danger' : '' }}">{{ $item->stok }} unit</b>
            </span>
        </div>
        <div class="progress progress-sm mb-2">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"></div>
        </div>
    </div>
    @empty
    <p class="text-center text-muted">Semua stok aman.</p>
    @endforelse
</div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-light" style="position: sticky; top: 0; z-index: 1;">
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Ganti $recentActivities dengan data dari Controller Anda --}}
                    @foreach($recentActivities ?? [] as $activity)
                    <tr>
                        <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $activity->barang->nama }}</td>
                        <td>
                            <span class="badge {{ $activity->type == 'masuk' ? 'badge-primary' : 'badge-info' }}">
                                {{ ucfirst($activity->type) }}
                            </span>
                        </td>
                        <td>{{ $activity->qty }}</td>
                        <td>{{ $activity->user->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('regularSellChart');
    const labels = {!! json_encode($chartData->pluck('hari')) !!};
    const data = {!! json_encode($chartData->pluck('total')) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Barang Keluar',
                data: data,
                backgroundColor: '#4e73df',
                borderRadius: 5,
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection