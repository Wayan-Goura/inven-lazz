<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DataBarang;
use App\Models\Category; // Diperlukan untuk fungsi create/index
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DetailTransaksi;
use App\Models\User; 


class TransaksiController extends Controller
{
    // Index Functions (b_keluar, b_masuk) 
   
    public function b_keluar()
    {
        $categories = Category::all();
        $transaksis = Transaksi::with('user', 'detailTransaksis.barang')
            ->where('tipe_transaksi', 'keluar')
            ->paginate(10);
        return view('pages.kel_barang.b_keluar.index', compact('transaksis', 'categories'));
    }

    public function b_masuk()
    {
        $categories = Category::all();
        $transaksis = Transaksi::with('user', 'detailTransaksis.barang')
            ->where('tipe_transaksi', 'masuk')
            ->paginate(10);
        return view('pages.kel_barang.b_masuk.index', compact('transaksis','categories'));
    }

    // --- Create Function - Sudah benar ---
    public function create()
    {
        
        // $categories = Category::all();
        $barangs = DataBarang::all();
        return view('pages.transaksi.create', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage (SATU ITEM PER TRANSAKSI).
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi',
            'tanggal_transaksi' => 'required|date',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            'data_barang_id' => 'required|exists:data_barangs,id',
            'jumlah' => 'required|integer|min:1',
            'lokasi' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $auth = auth()->user()->id;
            $barang = DataBarang::findOrFail($request->data_barang_id);
            $jumlahBarang = (int) $request->jumlah;
            $tipe = $request->tipe_transaksi;

            // Cek stok khusus barang keluar
            if ($tipe == 'keluar' && $barang->jml_stok < $jumlahBarang) {
                throw new Exception("Stok barang {$barang->nama_barang} tidak mencukupi. Sisa stok: {$barang->jml_stok}.");
            }

            // Simpan Transaksi Utama
            $transaksi = Transaksi::create([
                'kode_transaksi' => $validated['kode_transaksi'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'user_id' => auth()->user()->id,
                'tipe_transaksi' => $tipe,
                'total_barang' => $jumlahBarang,
                'lokasi' => $validated['lokasi']
            ]);

            // 4. Simpan Detail Transaksi
            $transaksi->detailTransaksis()->create([
                'data_barang_id' => $request->data_barang_id,
                'jumlah' => $jumlahBarang,
            ]);

            
            $tipe = $request->tipe_transaksi;
            $barang = DataBarang::find($request->data_barang_id);

            if ($tipe == 'masuk') {
                $barang->increment('jml_stok', $jumlahBarang);
            } else {
                $barang->decrement('jml_stok', $jumlahBarang);
            }
            // if ($barang) {
            //     if ($tipe == 'masuk') {
            //         $barang->increment('jml_stok', $jumlahBarang);

            //     } elseif ($tipe == 'keluar') {
            //         // Pengecekan stok
            //         if ($barang->jml_stok < $jumlahBarang) {
            //             throw new Exception("Stok barang {$barang->nama_barang} tidak mencukupi.");
            //         }
            //         $barang->decrement('jml_stok', $jumlahBarang);
            //     }
            // } else {
            //     throw new Exception("Data barang tidak ditemukan.");
            // }

            
            DB::commit();

            $redirectRoute =$tipe == 'masuk' 
            ? 'kel_barang.b_masuk.index'
            : 'kel_barang.b_keluar.index';

                // if ($tipe == 'masuk') {
                //     $redirectRoute = 'kel_barang.b_masuk.index'; // Ganti dengan nama route Barang Masuk Anda
                // } elseif ($tipe == 'keluar') {
                //     $redirectRoute = 'Kel_barang.b_keluar.index'; // Ganti dengan nama route Barang Keluar Anda
                // } else {
                //     $redirectRoute = 'home'; 
                // }

            return redirect()->route($redirectRoute)->with('success', 'Transaksi berhasil disimpan.');        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()])->withInput();
        }
    }

    // --- Edit Function - Sudah benar ---
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi,' . $transaksi->id,
            'tanggal_transaksi' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            'data_barang_id' => 'required|exists:data_barangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 1. Ambil data lama & Undo stok
            $detailLama = $transaksi->detailTransaksis()->first();
            if ($detailLama) {
                $barangLama = DataBarang::find($detailLama->data_barang_id);
                if ($barangLama) {
                    if ($transaksi->tipe_transaksi == 'masuk') {
                        $barangLama->decrement('jml_stok', $detailLama->jumlah);
                    } else {
                        $barangLama->increment('jml_stok', $detailLama->jumlah);
                    }
                }
                $detailLama->delete(); // Hapus detail lama
            }

            // 2. Siapkan data baru
            $barangBaru = DataBarang::findOrFail($request->data_barang_id);
            $jumlahBaru = (int) $request->jumlah;
            $tipeBaru = $request->tipe_transaksi;

            // 3. Validasi stok baru jika tipenya 'keluar'
            if ($tipeBaru == 'keluar' && $barangBaru->jml_stok < $jumlahBaru) {
                throw new Exception("Update Gagal: Stok {$barangBaru->nama_barang} tidak mencukupi.");
            }

            // 4. Update Header & Detail Baru
            $transaksi->update([
                'kode_transaksi' => $request->kode_transaksi,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'user_id' => $request->user_id,
                'tipe_transaksi' => $tipeBaru,
                'total_barang' => $jumlahBaru,
            ]);

            $transaksi->detailTransaksis()->create([
                'data_barang_id' => $barangBaru->id,
                'jumlah' => $jumlahBaru,
            ]);

            // 5. Update Stok Akhir
            if ($tipeBaru == 'masuk') {
                $barangBaru->increment('jml_stok', $jumlahBaru);
            } else {
                $barangBaru->decrement('jml_stok', $jumlahBaru);
            }

            DB::commit();

            $route = ($tipeBaru == 'masuk') ? 'kel_barang.b_masuk.index' : 'kel_barang.b_keluar.index';
            return redirect()->route($route)->with('success', 'Transaksi berhasil diperbarui.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage (SATU ITEM PER TRANSAKSI).
     */
    public function destroy(Transaksi $transaksi)
    {
        try {
            DB::beginTransaction();

            $tipe = $transaksi->tipe_transaksi;
            $detail = $transaksi->detailTransaksis->first(); // Ambil detail lama (hanya satu)

            // --- UNDO STOK ---
            if ($detail) {
                $barang = DataBarang::find($detail->data_barang_id);

                if ($barang) {
                    if ($tipe == 'masuk') {
                        $barang->decrement('jml_stok', $detail->jumlah); // UNDO: (-)
                    } elseif ($tipe == 'keluar') {
                        $barang->increment('jml_stok', $detail->jumlah); // UNDO: (+)
                    }
                }
                
                // Hapus detail setelah stok di-undo
                $detail->delete(); 
            }

            // Hapus Transaksi utama
            $transaksi->delete();

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}