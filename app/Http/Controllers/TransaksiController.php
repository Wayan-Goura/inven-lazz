<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DataBarang;
use App\Models\Category; // Diperlukan untuk fungsi create/index
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Diperlukan untuk fungsi edit (user_id)


class TransaksiController extends Controller
{
    // --- Index Functions (b_keluar, b_masuk) - Sudah benar ---
   
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
        $user_id_untuk_simpan = 1;
        // 1. Validasi Input
        $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi',
            'tanggal_transaksi' => 'required|date',
            // 'user_id' => 'required|exists:users,id',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            'data_barang_id' => 'required|exists:data_barangs,id', // Input tunggal
            'jumlah' => 'required|integer|min:1',                   // Input tunggal
        ]);

        try {
            DB::beginTransaction();
            $jumlahBarang = (int) $request->jumlah;
            $tipe = $request->tipe_transaksi;
            
            $transaksi = Transaksi::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'user_id' => $user_id_untuk_simpan,
                'tipe_transaksi' => $request->tipe_transaksi,
                'total_barang' => $jumlahBarang, 
            ]);

            $transaksi->detailTransaksis()->create([
                'data_barang_id' => $request->data_barang_id,
                'jumlah' => $jumlahBarang,
            ]);

            
            $tipe = $request->tipe_transaksi;
            $barang = DataBarang::find($request->data_barang_id);

            if ($barang) {
                if ($tipe == 'masuk') {
                    $barang->increment('jml_stok', $jumlahBarang);

                } elseif ($tipe == 'keluar') {
                    // Pengecekan stok
                    if ($barang->jml_stok < $jumlahBarang) {
                        throw new \Exception("Stok barang {$barang->nama_barang} tidak mencukupi.");
                    }
                    $barang->decrement('jml_stok', $jumlahBarang);
                }
            } else {
                throw new \Exception("Data barang tidak ditemukan.");
            }

            
            DB::commit();

            if ($tipe == 'masuk') {
                $redirectRoute = 'kel_barang.b_masuk.index'; // Ganti dengan nama route Barang Masuk Anda
            } elseif ($tipe == 'keluar') {
                $redirectRoute = 'Kel_barang.b_keluar.index'; // Ganti dengan nama route Barang Keluar Anda
            } else {
                $redirectRoute = 'home'; // Route fallback/default
            }

            // PERBAIKAN: Gunakan variabel tanpa tanda kutip
            return redirect()->route($redirectRoute)->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()])->withInput();
        }
    }

    // --- Edit Function - Sudah benar ---
    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('detailTransaksis');
        $barangs = DataBarang::all();
        // $users = User::all(); // Menggunakan use App\Models\User di atas

        $categories = Category::all();

        return view('pages.transaksi.edit', compact('transaksi', 'barangs', 'users', 'categories'));
    }

    /**
     * Update the specified resource in storage (SATU ITEM PER TRANSAKSI).
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        // 1. Validasi Data
        $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksis,kode_transaksi,' . $transaksi->id,
            'tanggal_transaksi' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'tipe_transaksi' => 'required|in:masuk,keluar',
            // Validasi tunggal
            'data_barang_id' => 'required|exists:data_barangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // --- UNDO STOK LAMA ---
            $tipeLama = $transaksi->tipe_transaksi;
            $detailLama = $transaksi->detailTransaksis->first(); // Ambil detail lama (hanya satu)
            
            if ($detailLama) {
                $barangLama = DataBarang::find($detailLama->data_barang_id);
                if ($barangLama) {
                    if ($tipeLama == 'masuk') {
                        $barangLama->decrement('jml_stok', $detailLama->jumlah); // UNDO: (-)
                    } elseif ($tipeLama == 'keluar') {
                        $barangLama->increment('jml_stok', $detailLama->jumlah); // UNDO: (+)
                    }
                }
                // Hapus detail lama agar bisa diganti
                $detailLama->delete();
            }
            
            // --- REDO TRANSAKSI BARU ---
            $jumlahBarangBaru = (int) $request->jumlah;
            $tipeBaru = $request->tipe_transaksi;

            // Perbarui Transaksi Utama
            $transaksi->update([
                'kode_transaksi' => $request->kode_transaksi,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'user_id' => $request->user_id,
                'tipe_transaksi' => $tipeBaru,
                'total_barang' => $jumlahBarangBaru,
            ]);

            // Buat Detail Transaksi Baru
            $transaksi->detailTransaksis()->create([
                'data_barang_id' => $request->data_barang_id,
                'jumlah' => $jumlahBarangBaru,
            ]);

            // --- REDO STOK BARU ---
            $barangBaru = DataBarang::find($request->data_barang_id);

            if ($barangBaru) {
                if ($tipeBaru == 'masuk') {
                    $barangBaru->increment('jml_stok', $jumlahBarangBaru); // REDO: (+)
                } elseif ($tipeBaru == 'keluar') {
                    if ($barangBaru->jml_stok < $jumlahBarangBaru) {
                        throw new \Exception("Stok barang {$barangBaru->nama_barang} tidak mencukupi untuk update.");
                    }
                    $barangBaru->decrement('jml_stok', $jumlahBarangBaru); // REDO: (-)
                }
            } else {
                throw new \Exception("Data barang untuk update tidak ditemukan.");
            }

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
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

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}