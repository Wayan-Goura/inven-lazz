<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Transaksi;
use App\Models\BarangReturn;
use Illuminate\Http\Request;

class PersetujuanController extends Controller
{
    /**
     * Menampilkan semua daftar permintaan persetujuan (Super Admin Only)
     */
    public function index()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        $data = [
            'barang' => DataBarang::where('is_disetujui', true)->with('category')->get(),
            'masuk' => Transaksi::where('is_disetujui', true)->where('tipe_transaksi', 'masuk')->with('dataBarang')->get(),
            'keluar' => Transaksi::where('is_disetujui', true)->where('tipe_transaksi', 'keluar')->with('dataBarang')->get(),
            'return' => BarangReturn::where('is_disetujui', true)->with('dataBarang')->get(),
        ];

        // Menghitung total semua permintaan untuk notifikasi
        $totalPersetujuan = $data['barang']->count() + $data['masuk']->count() + $data['keluar']->count() + $data['return']->count();

        return view('pages.persetujuan.index', compact('data', 'totalPersetujuan'));
    }

    /**
     * Memproses Setuju atau Tolak
     * @param string $type (barang|masuk|keluar|return)
     * @param int $id
     */
    public function proses(Request $request, $type, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $modelClass = match ($type) {
            'barang' => DataBarang::class,
            'masuk' => Transaksi::class,
            'keluar' => Transaksi::class,
            'return' => BarangReturn::class,
            default => abort(404),
        };

        $item = $modelClass::findOrFail($id);

        if ($request->action === 'setuju') {
            // Ambil data baru dari JSON
            $newData = $item->pending_perubahan;

            // LOGIKA UPDATE STOK OTOMATIS (Jika tipe adalah transaksi)
            if (in_array($type, ['masuk', 'keluar', 'return'])) {
                $barang = DataBarang::find($item->data_barang_id);

                if ($barang) {
                    // 1. Kembalikan stok lama dulu (Undo transaksi lama)
                    if ($type === 'masuk') {
                        $barang->decrement('jml_stok', $item->jumlah); // jumlah adalah nama kolom stok di tabel transaksi
                    } elseif ($type === 'keluar' || $type === 'return') {
                        $barang->increment('jml_stok', $item->jumlah);
                    }

                    // 2. Terapkan stok baru dari data yang disetujui
                    if ($type === 'masuk') {
                        $barang->increment('jml_stok', $newData['jumlah']);
                    } elseif ($type === 'keluar' || $type === 'return') {
                        // Cek jika stok cukup untuk barang keluar
                        if ($type === 'keluar' && $barang->jml_stok < $newData['jumlah']) {
                            return back()->with('error', 'Gagal! Stok tidak mencukupi untuk menyetujui pengeluaran ini.');
                        }
                        $barang->decrement('jml_stok', $newData['jumlah']);
                    }
                }
            }

            // Terapkan perubahan ke tabel transaksi/barang itu sendiri
            $item->update(array_merge($newData, [
                'pending_perubahan' => null,
                'is_disetujui' => false,
            ]));

            $message = "Perubahan $type disetujui dan stok telah diperbarui.";
        } else {
            // Jika Tolak
            $item->update([
                'pending_perubahan' => null,
                'is_disetujui' => false,
            ]);
            $message = "Perubahan $type ditolak.";
        }

        return redirect()->back()->with('success', $message);
    }
}