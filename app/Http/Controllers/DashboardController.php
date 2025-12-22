<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        // =========================
        // BARANG MASUK
        // =========================
        $barangMasuk = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'masuk')
            ->whereMonth('transaksis.created_at', $bulan)
            ->whereYear('transaksis.created_at', $tahun)
            ->sum('detail_transaksis.jumlah');

        // =========================
        // BARANG KELUAR
        // =========================
        $barangKeluar = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'keluar')
            ->whereMonth('transaksis.created_at', $bulan)
            ->whereYear('transaksis.created_at', $tahun)
            ->sum('detail_transaksis.jumlah');

        // =========================
        // BARANG RETURN
        // =========================
        $barangReturn = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.tipe_transaksi', 'return')
            ->whereMonth('transaksis.created_at', $bulan)
            ->whereYear('transaksis.created_at', $tahun)
            ->sum('detail_transaksis.jumlah');

        // =========================
        // STOK AKHIR (REAL)
        // =========================
        $stokAkhir = DB::table('data_barangs')->sum('jml_stok');

        // =========================
        // CHART 7 HARI TERAKHIR
        // =========================
        $chartData = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->selectRaw('DATE(transaksis.created_at) as tanggal, SUM(detail_transaksis.jumlah) as total')
            ->where('transaksis.tipe_transaksi', 'keluar')
            ->where('transaksis.created_at', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('dashboard', compact(
            'barangMasuk',
            'barangKeluar',
            'barangReturn',
            'stokAkhir',
            'chartData'
        ));
    }
}
