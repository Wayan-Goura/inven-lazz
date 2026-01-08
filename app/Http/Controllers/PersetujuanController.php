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
            'masuk'  => Transaksi::where('is_disetujui', true)->where('tipe_transaksi', 'masuk')->with('dataBarang')->get(),
            'keluar' => Transaksi::where('is_disetujui', true)->where('tipe_transaksi', 'keluar')->with('dataBarang')->get(),
            'return' => BarangReturn::where('is_disetujui', true)->with('dataBarang')->get(),
        ];

        $totalPersetujuan = $data['barang']->count() + $data['masuk']->count() + $data['keluar']->count() + $data['return']->count();

        return view('pages.persetujuan.index', compact('data', 'totalPersetujuan'));
    }

    /**
     * Menampilkan detail perubahan data (Data Lama vs Data Baru)
     */
    public function detail($type, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $item = $this->resolveModel($type, $id);

        return view('pages.persetujuan.detail', compact('item', 'type'));
    }

    /**
     * Memproses Setuju atau Tolak
     */
    public function proses(Request $request, $type, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $item = $this->resolveModel($type, $id);

        if ($request->action === 'setuju') {
            $newData = $item->pending_perubahan;

            // Logika Update Stok Otomatis (Tetap sesuai aslinya)
            if (in_array($type, ['masuk', 'keluar', 'return'])) {
                $barang = DataBarang::find($item->data_barang_id);

                if ($barang) {
                    // 1. Undo stok lama
                    if ($type === 'masuk') {
                        $barang->decrement('jml_stok', $item->jumlah);
                    } elseif ($type === 'keluar' || $type === 'return') {
                        $barang->increment('jml_stok', $item->jumlah);
                    }

                    // 2. Terapkan stok baru
                    if ($type === 'masuk') {
                        $barang->increment('jml_stok', $newData['jumlah']);
                    } elseif ($type === 'keluar' || $type === 'return') {
                        if ($type === 'keluar' && $barang->jml_stok < $newData['jumlah']) {
                            return back()->with('error', 'Gagal! Stok tidak mencukupi untuk menyetujui pengeluaran ini.');
                        }
                        $barang->decrement('jml_stok', $newData['jumlah']);
                    }
                }
            }

            // Cek apakah ini permintaan hapus (Delete)
            if (isset($newData['is_delete']) && $newData['is_delete'] == true) {
                $item->delete();
                $message = "Data $type berhasil dihapus.";
            } else {
                // Terapkan update
                $item->update(array_merge($newData, [
                    'pending_perubahan' => null,
                    'is_disetujui' => false,
                ]));
                $message = "Perubahan $type disetujui dan data telah diperbarui.";
            }
        } else {
            // Jika Tolak
            $item->update([
                'pending_perubahan' => null,
                'is_disetujui' => false,
            ]);
            $message = "Perubahan $type ditolak.";
        }

        // Redirect ke index jika dari halaman detail, atau back jika dari index
        return redirect()->route('persetujuan.index')->with('success', $message);
    }

    /**
     * Helper untuk menentukan model berdasarkan type
     */
    private function resolveModel($type, $id)
    {
        $modelClass = match ($type) {
            'barang' => DataBarang::class,
            'masuk', 'keluar' => Transaksi::class,
            'return' => BarangReturn::class,
            default => abort(404),
        };

        return $modelClass::findOrFail($id);
    }
}