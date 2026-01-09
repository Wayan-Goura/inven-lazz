<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DataBarang;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Mpdf\Mpdf;

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
    //     $transaksis = Transaksi::where('tipe_transaksi', 'masuk')->get();

    //     dd(
    //         $transaksis->map(fn ($t) => [
    //             'id' => $t->id,
    //             'is_disetujui' => $t->is_disetujui,
    //             'pending_perubahan' => $t->pending_perubahan,
    //         ])
    //     );
    // }

    /* =============================
     * CREATE
     * ============================= */
    public function create()
    {
        $barangs = DataBarang::all();
        return view('pages.transaksi.create', compact('barangs'));
    }

    /* =============================
     * GENERATE KODE
     * ============================= */
    public function getGenerateCode(Request $request)
    {
        $tipe = $request->type; // 'masuk' atau 'keluar'
        $prefix = ($tipe == 'masuk') ? 'BM' : 'BK';

        $lastTransaksi = Transaksi::where('tipe_transaksi', $tipe)
            ->latest('id')
            ->first();

        $lastNumber = 0;
        if ($lastTransaksi && $lastTransaksi->kode_transaksi) {
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
     * EDIT
     * ============================= */
    public function edit($id)
    {
        $transaksi = Transaksi::with('detailTransaksis.barang')->findOrFail($id);
        $barangs = DataBarang::all();
        $categories = Category::all();

        $folder = ($transaksi->tipe_transaksi === 'masuk') ? 'b_masuk' : 'b_keluar';

        return view("pages.kel_barang.{$folder}.edit", compact('transaksi', 'barangs', 'categories'));
    }

    /* =============================
     * UPDATE
     * ============================= */
    /**
     * UPDATE
     * Pastikan parameter kedua dinamai $id atau menyesuaikan dengan route {barang}
     */
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $role = auth()->user()->role;

        // dd([
        //     'role' => auth()->user()->role,
        //     'is_super_admin' => auth()->user()->role === 'super_admin',
        // ]);

        // 1. Validasi
        $validated = $request->validate([
            'tanggal_transaksi' => 'required|date',
            'data_barang_id' => 'required|exists:data_barangs,id',
            'jumlah' => 'required|integer|min:1',
            'lokasi' => 'required|string',
        ]);

        // Proteksi admin biasa
        if ($transaksi->is_disetujui && $role !== 'super_admin') {
            return back()->with('error', 'Transaksi ini sedang menunggu persetujuan.');
        }

        // =========================
        // SUPER ADMIN → LANGSUNG UPDATE
        // =========================
        if ($role === 'super_admin') {
            DB::beginTransaction();
            try {
                // dd('STEP 1: masuk super admin');
                $detail = $transaksi->detailTransaksis()->firstOrFail();
                

                $barangLama = DataBarang::findOrFail($detail->data_barang_id);
                
                
                if ($transaksi->tipe_transaksi === 'masuk') {
                    $barangLama->decrement('jml_stok', $detail->jumlah);
                } else {
                    $barangLama->increment('jml_stok', $detail->jumlah);
                }

                $barangBaru = DataBarang::findOrFail($request->data_barang_id);
                if ($transaksi->tipe_transaksi === 'keluar' && $barangBaru->jml_stok < $request->jumlah) {
                    throw new \Exception('Stok tidak mencukupi');
                }

                if ($transaksi->tipe_transaksi === 'masuk') {
                    $barangBaru->increment('jml_stok', $request->jumlah);
                } else {
                    $barangBaru->decrement('jml_stok', $request->jumlah);
                }

                $transaksi->update([
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'lokasi' => $request->lokasi,
                    'total_barang' => $request->jumlah, // 🔥 FIX
                    'pending_perubahan' => null,
                    'is_disetujui' => false,
                ]);

                $detail->update([
                    'data_barang_id' => $request->data_barang_id,
                    'jumlah' => $request->jumlah,
                ]);

                DB::commit();

                return redirect()->route(
                    $transaksi->tipe_transaksi === 'masuk'
                    ? 'kel_barang.b_masuk.index'
                    : 'kel_barang.b_keluar.index'
                )->with('success', 'Transaksi berhasil diperbarui.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', $e->getMessage());
            }
        }


        // =========================
        // ADMIN → MASUK PENDING
        // =========================
        $transaksi->update([
            'pending_perubahan' => $validated,
            'is_disetujui' => true,
        ]);

        $route = $transaksi->tipe_transaksi === 'masuk'
            ? 'kel_barang.b_masuk.index'
            : 'kel_barang.b_keluar.index';

        return redirect()->route($route)
            ->with('success', 'Perubahan telah diajukan ke Super Admin.');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::with('detailTransaksis')->findOrFail($id);
        $role = auth()->user()->role;

        // Proteksi: kalau sudah pending, admin tidak bisa hapus lagi
        if ($transaksi->pending_perubahan && $role !== 'super_admin') {
            return back()->with('error', 'Transaksi ini sedang menunggu persetujuan.');
        }

        /**
         * ==================================
         * SUPER ADMIN → LANGSUNG HAPUS
         * ==================================
         */
        if ($role === 'super_admin') {
            DB::beginTransaction();
            try {
                $detail = $transaksi->detailTransaksis()->first();

                if ($detail) {
                    $barang = DataBarang::findOrFail($detail->data_barang_id);

                    // Kembalikan stok
                    if ($transaksi->tipe_transaksi === 'masuk') {
                        $barang->decrement('jml_stok', $detail->jumlah);
                    } else {
                        $barang->increment('jml_stok', $detail->jumlah);
                    }
                }

                // Hapus transaksi (detail ikut terhapus via cascade)
                $transaksi->delete();

                DB::commit();

                return redirect()
                    ->route(
                        $transaksi->tipe_transaksi === 'masuk'
                        ? 'kel_barang.b_masuk.index'
                        : 'kel_barang.b_keluar.index'
                    )
                    ->with('success', 'Transaksi berhasil dihapus.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
            }
        }

        /**
         * ==================================
         * ADMIN → AJUKAN PERSETUJUAN
         * ==================================
         */
        $transaksi->update([
            'pending_perubahan' => [
                'is_delete' => true
            ],
            'is_disetujui' => true,
        ]);

        return redirect()
            ->route(
                $transaksi->tipe_transaksi === 'masuk'
                ? 'kel_barang.b_masuk.index'
                : 'kel_barang.b_keluar.index'
            )
            ->with('success', 'Permintaan penghapusan dikirim ke Super Admin.');
    }


    /* =============================
     * CETAK PDF BARANG MASUK
     * ============================= */
    public function cetak_masuk_pdf()
    {
        $transaksis = Transaksi::with(['user', 'detailTransaksis.barang'])
            ->where('tipe_transaksi', 'masuk')
            ->get();

        $html = view('pages.kel_barang.b_masuk.cetak_masuk_pdf', [
            'transaksis' => $transaksis,
            'title' => 'Laporan Barang Masuk',
        ])->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 10,
            'margin_footer' => 10,
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('laporan_barang_masuk.pdf', 'I');
    }

    /* =============================
     * CETAK PDF BARANG KELUAR
     * ============================= */
    public function cetak_keluar_pdf()
    {
        $transaksis = Transaksi::with(['user', 'detailTransaksis.barang'])
            ->where('tipe_transaksi', 'keluar')
            ->get();

        $html = view('pages.kel_barang.b_keluar.cetak_keluar_pdf', [
            'transaksis' => $transaksis,
            'title' => 'Laporan Barang Keluar',
        ])->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 10,
            'margin_footer' => 10,
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('laporan_barang_keluar.pdf', 'I');
    }
}