<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DataBarang;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TransaksiController extends Controller
{
    public function b_masuk()
    {
        $transaksis = Transaksi::with('user', 'detailTransaksis.barang')
            ->where('tipe_transaksi', 'masuk')
            ->latest()
            ->paginate(10);

        return view('pages.kel_barang.b_masuk.index', compact('transaksis'));
    }

    public function b_keluar()
    {
        $transaksis = Transaksi::with('user', 'detailTransaksis.barang')
            ->where('tipe_transaksi', 'keluar')
            ->latest()
            ->paginate(10);

        return view('pages.kel_barang.b_keluar.index', compact('transaksis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::with('detailTransaksis')->findOrFail($id);
            $detail = $transaksi->detailTransaksis->first();
            
            if (!$detail) {
                throw new Exception('Detail transaksi tidak ditemukan.');
            }

            $barang = DataBarang::findOrFail($detail->data_barang_id);

            $oldJumlah = $detail->jumlah;
            $newJumlah = $request->jumlah;
            $diff = $newJumlah - $oldJumlah; // Selisih perubahan

            if ($transaksi->tipe_transaksi === 'masuk') {
                // BARANG MASUK: Jika jumlah ditambah (+), stok gudang bertambah
                // Jika jumlah dikurangi (-), stok gudang berkurang
                if ($diff < 0 && $barang->jml_stok < abs($diff)) {
                    throw new Exception('Gagal update: Stok gudang tidak mencukupi jika jumlah barang masuk dikurangi!');
                }
                $barang->increment('jml_stok', $diff);
            } else {
                // BARANG KELUAR: Jika jumlah ditambah (+), stok gudang berkurang
                // Jika jumlah dikurangi (-), stok gudang bertambah
                if ($diff > 0 && $barang->jml_stok < $diff) {
                    throw new Exception('Gagal update: Stok gudang tidak mencukupi untuk menambah jumlah pengeluaran!');
                }
                $barang->decrement('jml_stok', $diff);
            }

            // Update tabel detail dan tabel utama agar sinkron
            $detail->update(['jumlah' => $newJumlah]);
            $transaksi->update(['total_barang' => $newJumlah]);

            DB::commit();

            $route = ($transaksi->tipe_transaksi === 'masuk') 
                     ? 'kel_barang.b_masuk.index' 
                     : 'kel_barang.b_keluar.index';

            return redirect()->route($route)->with('success', 'Stok berhasil diperbarui dan disinkronkan.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy(Transaksi $transaksi)
    {
        DB::beginTransaction();
        try {
            $detail = $transaksi->detailTransaksis->first();
            $barang = DataBarang::find($detail->data_barang_id);

            if ($barang) {
                if ($transaksi->tipe_transaksi === 'masuk') {
                    $barang->decrement('jml_stok', $detail->jumlah);
                } else {
                    $barang->increment('jml_stok', $detail->jumlah);
                }
            }

            $detail->delete();
            $transaksi->delete();

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }
    
    // ... (Fungsi b_keluar, create, store, edit, getGenerateCode tetap seperti sebelumnya)
}