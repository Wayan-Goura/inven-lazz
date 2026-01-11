<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\BarangReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersetujuanController extends Controller
{
    /**
     * ======================
     * DAFTAR PERSETUJUAN
     * ======================
     */
    public function index()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        $data = [
            'barang' => DataBarang::where('is_disetujui', true)->with('category')->get(),
            'masuk' => Transaksi::where('is_disetujui', true)
                ->where('tipe_transaksi', 'masuk')
                ->with('dataBarang')->get(),
            'keluar' => Transaksi::where('is_disetujui', true)
                ->where('tipe_transaksi', 'keluar')
                ->with('dataBarang')->get(),
            'return' => BarangReturn::where('is_disetujui', true)
                ->with('dataBarang')->get(),
        ];
        

        $totalPersetujuan =
            $data['barang']->count() +
            $data['masuk']->count() +
            $data['keluar']->count() +
            $data['return']->count();

        return view('pages.persetujuan.index', compact('data', 'totalPersetujuan'));
    }

    /**
     * ======================
     * DETAIL PERUBAHAN
     * ======================
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
     * ======================
     * PROSES SETUJU / TOLAK
     * ======================
     */
    public function proses(Request $request, $type, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $item = $this->resolveModel($type, $id);
        $newData = $item->pending_perubahan;

        if (!$newData || !is_array($newData)) {
            return redirect()->route('persetujuan.index')
                ->with('error', 'Data perubahan tidak ditemukan.');
        }

        // ======================
        // JIKA DITOLAK
        // ======================
        if ($request->action !== 'setuju') {
            $item->update([
                'pending_perubahan' => null,
                'is_disetujui' => false
            ]);

            return redirect()->route('persetujuan.index')
                ->with('error', 'Perubahan ditolak.');
        }

        // ======================
        // JIKA PERMINTAAN DELETE
        // ======================
        if (!empty($newData['is_delete'])) {

            DB::beginTransaction();
            try {
                $detail = $item->detailTransaksis()->first();

                if ($detail) {
                    $barang = DataBarang::findOrFail($detail->data_barang_id);

                    if ($item->tipe_transaksi === 'masuk') {
                        $barang->decrement('jml_stok', $detail->jumlah);
                    } else {
                        $barang->increment('jml_stok', $detail->jumlah);
                    }
                }

                $item->delete();

                DB::commit();

                return redirect()->route('persetujuan.index')
                    ->with('success', 'Penghapusan transaksi berhasil disetujui.');

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('persetujuan.index')
                    ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
            }
        }

        // ======================
        // JIKA UPDATE MASUK / KELUAR
        // ======================
        if (in_array($type, ['masuk', 'keluar'])) {

            DB::beginTransaction();
            try {
                $detail = $item->detailTransaksis()->firstOrFail();

                // rollback stok lama
                $barangLama = DataBarang::findOrFail($detail->data_barang_id);
                if ($item->tipe_transaksi === 'masuk') {
                    $barangLama->decrement('jml_stok', $detail->jumlah);
                } else {
                    $barangLama->increment('jml_stok', $detail->jumlah);
                }

                $newBarangId = $newData['data_barang_id'];
                $newJumlah = $newData['jumlah'];

                // terapkan stok baru
                $barangBaru = DataBarang::findOrFail($newBarangId);
                if ($item->tipe_transaksi === 'keluar' && $barangBaru->jml_stok < $newJumlah) {
                    throw new \Exception('Stok tidak mencukupi.');
                }

                if ($item->tipe_transaksi === 'masuk') {
                    $barangBaru->increment('jml_stok', $newJumlah);
                } else {
                    $barangBaru->decrement('jml_stok', $newJumlah);
                }

                $item->update([
                    'tanggal_transaksi' => substr($newData['tanggal_transaksi'], 0, 10),
                    'lokasi' => $newData['lokasi'],
                    'total_barang' => $newJumlah,
                    'pending_perubahan' => null,
                    'is_disetujui' => false,
                ]);

                $detail->update([
                    'data_barang_id' => $newBarangId,
                    'jumlah' => $newJumlah,
                ]);

                DB::commit();

                return redirect()->route('persetujuan.index')
                    ->with('success', 'Perubahan berhasil disetujui.');

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('persetujuan.index')
                    ->with('error', $e->getMessage());
            }
        }

        // ======================
        // TYPE LAIN (BARANG / RETURN)
        // ======================
        $item->update(array_merge($newData, [
            'pending_perubahan' => null,
            'is_disetujui' => false,
        ]));

        return redirect()->route('persetujuan.index')
            ->with('success', 'Berhasil diproses.');
    }




    /**
     * ======================
     * RESOLVE MODEL
     * ======================
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
