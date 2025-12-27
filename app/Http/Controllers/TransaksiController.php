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
     * generate kode
     * ============================= */
    public function getGenerateCode(Request $request)
    {
        $tipe = $request->type; // 'masuk' atau 'keluar'

        // Tentukan prefix berdasarkan tipe
        $prefix = ($tipe == 'masuk') ? 'BM' : 'BK';

        // Cari transaksi terakhir dengan tipe yang sama
        $lastTransaksi = Transaksi::where('tipe_transaksi', $tipe)
            ->latest('id')
            ->first();

        $lastNumber = 0;
        if ($lastTransaksi && $lastTransaksi->kode_transaksi) {
            // Mengambil 3 digit terakhir dari kode (contoh: BK-202512-001)
            $lastNumber = intval(substr($lastTransaksi->kode_transaksi, -3));
        }

        $newCode = $prefix . '-' . date('Ym') . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'code' => $newCode
        ]);
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
public function edit($id)
{
    $transaksi = Transaksi::with('detailTransaksis.barang')->findOrFail($id);
    $barangs = DataBarang::all();
    $categories = Category::all();

    // Sesuaikan dengan struktur folder: pages.kel_barang.b_masuk.edit
    $folder = ($transaksi->tipe_transaksi === 'masuk') ? 'b_masuk' : 'b_keluar';
    
    return view("pages.kel_barang.{$folder}.edit", compact('transaksi', 'barangs', 'categories'));
}
// UPDATE
public function update(Request $request, $id)
{
    $request->validate([
        'tanggal_transaksi' => 'required|date',
        'data_barang_id' => 'required|exists:data_barangs,id',
        'jumlah' => 'required|integer|min:1',
        'lokasi' => 'required|string',
    ]);

    DB::beginTransaction();
    try {
        $transaksi = Transaksi::findOrFail($id);
        $detail = $transaksi->detailTransaksis->first();

        // 1. REVERT STOK LAMA
        $barangLama = DataBarang::findOrFail($detail->data_barang_id);
        if ($transaksi->tipe_transaksi === 'masuk') {
            $barangLama->jml_stok -= $detail->jumlah;
        } else {
            $barangLama->increment('jml_stok', $detail->jumlah);
        }
        $barangLama->save();

        // 2. APPLY STOK BARU
        $barangBaru = DataBarang::findOrFail($request->data_barang_id);
        if ($transaksi->tipe_transaksi === 'keluar' && $barangBaru->jml_stok < $request->jumlah) {
            throw new \Exception('Stok tidak mencukupi!');
        }

        if ($transaksi->tipe_transaksi === 'masuk') {
            $barangBaru->jml_stok += $request->jumlah;
        } else {
            $barangBaru->jml_stok -= $request->jumlah;
        }
        $barangBaru->save();

        // 3. UPDATE DATA TRANSAKSI UTAMA
        $transaksi->update([
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'total_barang' => $request->jumlah,
            'lokasi' => $request->lokasi,
        ]);

        // 4. UPDATE DETAIL TRANSAKSI (MENGGUNAKAN QUERY BUILDER UNTUK MENGHINDARI ERROR 'ID IS NULL')
        // Ini akan mencari berdasarkan transaksi_id, bukan id primary key detail
        DB::table('detail_transaksis')
            ->where('transaksi_id', $transaksi->id)
            ->update([
                'data_barang_id' => $request->data_barang_id,
                'jumlah' => $request->jumlah,
                'updated_at' => now()
            ]);

        DB::commit();

        // Redirect sesuai route list Anda (transaksi.index tidak ada, gunakan b_masuk/b_keluar)
        $route = ($transaksi->tipe_transaksi === 'masuk') ? 'kel_barang.b_masuk.index' : 'kel_barang.b_keluar.index';
        
        return redirect()->route($route)->with('success', 'Data Berhasil Diperbarui');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors($e->getMessage())->withInput();
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
