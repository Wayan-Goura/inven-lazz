<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DataBarang;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TransaksiController extends Controller
{
    /* =============================
     * BARANG KELUAR
     * ============================= */
    public function b_keluar()
    {
        $categories = Category::all();
        $transaksis = Transaksi::with('user', 'detailTransaksis.barang')
            ->where('tipe_transaksi', 'keluar')
            ->paginate(10);

        return view('pages.kel_barang.b_keluar.index', compact('transaksis', 'categories'));
    }

    /* =============================
     * BARANG MASUK
     * ============================= */
    public function b_masuk()
    {
        $categories = Category::all();
        $transaksis = Transaksi::with('user', 'detailTransaksis.barang')
            ->where('tipe_transaksi', 'masuk')
            ->paginate(10);

        return view('pages.kel_barang.b_masuk.index', compact('transaksis', 'categories'));
    }

    /* =============================
     * CREATE
     * ============================= */
    public function create()
    {
        $barangs = DataBarang::all();
        return view('pages.transaksi.create', compact('barangs'));
    }

    /* =============================
     * STORE
     * ============================= */
    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:transaksis',
            'tanggal_transaksi' => 'required|date',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            'data_barang_id' => 'required|exists:data_barangs,id',
            'jumlah' => 'required|integer|min:1',
            'lokasi' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $barang = DataBarang::findOrFail($request->data_barang_id);

            if (
                $request->tipe_transaksi === 'keluar' &&
                $barang->jml_stok < $request->jumlah
            ) {
                throw new Exception('Stok barang tidak mencukupi');
            }

            $transaksi = Transaksi::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'user_id' => auth()->id(),
                'tipe_transaksi' => $request->tipe_transaksi,
                'total_barang' => $request->jumlah,
                'lokasi' => $request->lokasi,
            ]);

            $transaksi->detailTransaksis()->create([
                'data_barang_id' => $request->data_barang_id,
                'jumlah' => $request->jumlah,
            ]);

            if ($request->tipe_transaksi === 'masuk') {
                $barang->increment('jml_stok', $request->jumlah);
            } else {
                $barang->decrement('jml_stok', $request->jumlah);
            }

            DB::commit();

            return redirect()->route(
                $request->tipe_transaksi === 'masuk'
                    ? 'kel_barang.b_masuk.index'
                    : 'kel_barang.b_keluar.index'
            )->with('success', 'Transaksi berhasil disimpan');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    /* =============================
     * DELETE
     * ============================= */
    public function destroy(Transaksi $transaksi)
    {
        DB::beginTransaction();

        try {
            $detail = $transaksi->detailTransaksis->first();
            $barang = DataBarang::find($detail->data_barang_id);

            if ($transaksi->tipe_transaksi === 'masuk') {
                $barang->decrement('jml_stok', $detail->jumlah);
            } else {
                $barang->increment('jml_stok', $detail->jumlah);
            }

            $detail->delete();
            $transaksi->delete();

            DB::commit();

            return redirect()->route(
                $transaksi->tipe_transaksi === 'masuk'
                    ? 'kel_barang.b_masuk.index'
                    : 'kel_barang.b_keluar.index'
            )->with('success', 'Transaksi berhasil dihapus');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }
}
