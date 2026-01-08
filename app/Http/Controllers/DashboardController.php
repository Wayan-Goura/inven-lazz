<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulan = now()->month;
        $tahun = now()->year;
        $hari  = Carbon::today();

        // =========================
        // DATA BULAN INI (ASLI)
        // =========================
        $barangMasuk = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'masuk')
            ->whereMonth('transaksis.created_at', $bulan)
            ->whereYear('transaksis.created_at', $tahun)
            ->sum('detail_transaksis.jumlah') ?? 0;

        $barangKeluar = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'keluar')
            ->whereMonth('transaksis.created_at', $bulan)
            ->whereYear('transaksis.created_at', $tahun)
            ->sum('detail_transaksis.jumlah') ?? 0;

        $barangReturn = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'return')
            ->whereMonth('transaksis.created_at', $bulan)
            ->whereYear('transaksis.created_at', $tahun)
            ->sum('detail_transaksis.jumlah') ?? 0;

        // =========================
        // DATA HARI INI
        // =========================
        $masukHariIni = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'masuk')
            ->whereDate('transaksis.created_at', $hari)
            ->sum('detail_transaksis.jumlah') ?? 0;

        $keluarHariIni = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'keluar')
            ->whereDate('transaksis.created_at', $hari)
            ->sum('detail_transaksis.jumlah') ?? 0;

        $returnHariIni = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'return')
            ->whereDate('transaksis.created_at', $hari)
            ->sum('detail_transaksis.jumlah') ?? 0;

        // =========================
        // DATA TAHUN INI
        // =========================
        $masukTahunIni = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'masuk')
            ->whereYear('transaksis.created_at', $tahun)
            ->sum('detail_transaksis.jumlah') ?? 0;

        $keluarTahunIni = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'keluar')
            ->whereYear('transaksis.created_at', $tahun)
            ->sum('detail_transaksis.jumlah') ?? 0;

        $returnTahunIni = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'return')
            ->whereYear('transaksis.created_at', $tahun)
            ->sum('detail_transaksis.jumlah') ?? 0;

        // =========================
        // STOK AKHIR & USER (UNTUK KARTU)
        // =========================
        $stokAkhir = DB::table('data_barangs')->sum('jml_stok') ?? 0;
        $totalDataBarang = DB::table('data_barangs')->count();
        $totalUser = DB::table('users')->count(); // Data tambahan untuk gambar

        // =========================
        // DATA UNTUK GRAFIK PERBANDINGAN (6 BULAN TERAKHIR)
        // =========================
        $sixMonthsData = DB::table('transaksis')
            ->selectRaw("DATE_FORMAT(created_at, '%b') as bulan, 
                        SUM(CASE WHEN tipe_transaksi = 'masuk' THEN 1 ELSE 0 END) as masuk,
                        SUM(CASE WHEN tipe_transaksi = 'keluar' THEN 1 ELSE 0 END) as keluar")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('created_at', 'asc')
            ->get();

        $chartData = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->selectRaw('DATE(transaksis.created_at) as hari, SUM(detail_transaksis.jumlah) as total')
            ->where('transaksis.tipe_transaksi', 'keluar')
            ->where('transaksis.created_at', '>=', now()->subDays(7))
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        // =========================
        // DATA AKTIVITAS & STOK MENIPIS
        // =========================
        $stokMenipis = DB::table('data_barangs')
            ->select('nama_barang', 'jml_stok as stok')
            ->where('jml_stok', '<', 10)
            ->get();

        $recentActivities = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->join('data_barangs', 'detail_transaksis.data_barang_id', '=', 'data_barangs.id')
            ->join('users', 'transaksis.user_id', '=', 'users.id')
            ->select('transaksis.created_at', 'data_barangs.nama_barang', 'transaksis.tipe_transaksi as type', 'detail_transaksis.jumlah as qty', 'users.name as user_name')
            ->orderBy('transaksis.created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'barangMasuk', 'barangKeluar', 'barangReturn',
            'masukHariIni', 'keluarHariIni', 'returnHariIni',
            'masukTahunIni', 'keluarTahunIni', 'returnTahunIni',
            'stokAkhir', 'totalDataBarang', 'chartData', 'stokMenipis',
            'totalUser', 'sixMonthsData', 'recentActivities'
        ));
    }
}