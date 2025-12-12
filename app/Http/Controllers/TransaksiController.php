<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DataBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function b_keluar()
    {

        $transaksis = Transaksi::with('user', 'detailTransaksis.barang')
        ->where('tipe_transaksi', 'keluar')
        ->paginate(10);
        return view('pages.kel_barang.b_keluar.index', compact('transaksis'));
    }
    public function b_masuk()
    {
        $transaksis = Transaksi::with('user', 'detailTransaksis.barang')
        ->where('tipe_transaksi', 'masuk')
        ->paginate(10);
        return view('pages.kel_barang.b_masuk.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = DataBarang::all();
        // tambahkan untuk mengambil data user nanti
        return view('pages.transaksi.create', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // Validasi input
        $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi',
            'tanggal_transaksi' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'tipe_transaksi' => 'required|string',
            'total_barang' => 'required|integer|min:1',
            'detail_transaksis' => 'required|array|min:1',
            'detail_transaksis.*.data_barang_id' => 'required|exists:data_barangs,id',
            'detail_transaksis.*.jumlah' => 'required|integer|min:1',
        ]);
        try {
            // Mulai transaksi database
            \DB::beginTransaction();

            $totalBarang = collect($request->input('detail_transaksis'))->sum('jumlah');

            // Buat transaksi utama
            $transaksi = Transaksi::create($request->only([
                'kode_transaksi' => $request->input('kode_transaksi'),
                'tanggal_transaksi' => $request->input('tanggal_transaksi'),
                'user_id' => $request->input('user_id'),
                'tipe_transaksi' => $request->input('tipe_transaksi'),
                'total_barang' => $totalBarang,
            ]));

            // Buat detail transaksi
            foreach ($request->input('detail_transaksis') as $detail) {
                $transaksi->detailTransaksis()->create($detail);
            }

            // Commit transaksi database
            \DB::commit();

            return redirect()->route('transaksis.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi error
            \DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage()])->withInput();
        }

    }

    /**
     * Display the specified resource.
     */
    // public function show(Transaksi $transaksi)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        // Memuat detail transaksi yang sudah ada dan semua data yang diperlukan
        $transaksi->load('detailTransaksis');
        $barangs = DataBarang::all();
        $users = \App\Models\User::all();

        return view('transaksi.edit', compact('transaksi', 'barangs', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        // 1. Validasi Data
        $request->validate([
            // Pastikan kode_transaksi unik, kecuali untuk transaksi saat ini
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi,' . $transaksi->id,
            'tanggal_transaksi' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            'details' => 'required|array|min:1',
            'details.*.data_barang_id' => 'required|exists:data_barangs,id',
            'details.*.jumlah' => 'required|integer|min:1',
        ]);

        try {
            // Menggunakan transaksi database
            DB::beginTransaction();

            // 2. Hitung Total Barang baru
            $totalBarang = collect($request->details)->sum('jumlah');

            // 3. Perbarui Transaksi Utama
            $transaksi->update([
                'kode_transaksi' => $request->kode_transaksi,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'user_id' => $request->user_id,
                'tipe_transaksi' => $request->tipe_transaksi,
                'total_barang' => $totalBarang,
            ]);

            // 4. Hapus Detail Transaksi Lama
            $transaksi->detailTransaksis()->delete();

            // 5. Siapkan Data Detail Baru
            $details = [];
            foreach ($request->details as $detail) {
                $details[] = [
                    'data_barang_id' => $detail['data_barang_id'],
                    'jumlah' => $detail['jumlah'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // 6. Simpan Detail Transaksi Baru
            $transaksi->detailTransaksis()->createMany($details);

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        try {
            DB::beginTransaction();

            // Hapus semua detail transaksi terlebih dahulu
            $transaksi->detailTransaksis()->delete();

            // Hapus Transaksi utama
            $transaksi->delete();

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi.');
        }
    }
}
