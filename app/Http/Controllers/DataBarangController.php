<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class DataBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = DataBarang::with('category')->latest();

        // SEARCH (kode / nama)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('k_barang', 'like', "%{$search}%")
                    ->orWhere('merek', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%");
            });
        }

        // FILTER KATEGORI
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // FILTER TANGGAL
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // PAGINATION
        $perPage = $request->get('per_page', 10);
        if ($perPage === 'all') {
            $perPage = $query->count();
        }

        $dataBarangs = $query->paginate((int) $perPage)->withQueryString();
        $categories = Category::all();

        return view('pages.barang.index', compact('dataBarangs', 'categories', 'perPage'));
    }

    public function create()
    {
        $categories = Category::all();

        // Ambil kode barang yang sudah ada
        $existingBarangs = DataBarang::select(
            'id',
            'k_barang',
            'nama_barang',
            'merek',
            'category_id'
        )->get();

        return view('pages.barang.create', compact(
            'categories',
            'existingBarangs'
        ));
    }

    public function store(Request $request)
    {
        // Cek apakah kode barang sudah ada
        $existingBarang = DataBarang::where('k_barang', $request->k_barang)->first();

        // =========================
        // JIKA KODE BARANG SUDAH ADA
        // =========================
        if ($existingBarang) {

            $request->validate([
                'jml_stok' => 'required|integer|min:1',
            ]);

            // Tambahkan stok saja
            $existingBarang->increment('jml_stok', $request->jml_stok);

            return redirect()
                ->route('barang.index')
                ->with('success', 'Stok barang berhasil ditambahkan.');
        }

        // =========================
        // JIKA KODE BARANG BARU
        // =========================
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'k_barang' => 'required|string|unique:data_barangs,k_barang',
            'jml_stok' => 'required|integer|min:0',
        ]);

        DataBarang::create($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = DataBarang::findOrFail($id);

        // Opsional: Admin tidak boleh masuk halaman edit jika barang sedang pending
        if (auth()->user()->role !== 'super_admin' && $barang->is_disetujui) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang ini sedang dalam proses persetujuan dan tidak bisa diubah.');
        }

        $categories = Category::all();
        return view('pages.barang.edit', compact('barang', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $barang = DataBarang::findOrFail($id);

        if (auth()->user()->role !== 'super_admin' && $barang->is_disetujui) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang ini sedang menunggu persetujuan, tidak dapat diubah kembali.');
        }

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'k_barang' => 'required|string|unique:data_barangs,k_barang,' . $barang->id,
            'jml_stok' => 'required|integer|min:0',
        ]);

        if (auth()->user()->role === 'super_admin') {
            $barang->update(array_merge($validated, [
                'pending_perubahan' => null,
                'is_disetujui' => false
            ]));
            $msg = 'Barang berhasil diperbarui secara langsung.';
        } else {
            $barang->update([
                'pending_perubahan' => $validated,
                'is_disetujui' => true
            ]);
            $msg = 'Perubahan data telah diajukan.';
        }

        return redirect()->route('barang.index')->with('success', $msg);
    }

    public function destroy(DataBarang $barang)
    {
        $barang = DataBarang::findOrFail($barang->id);

        if ($barang->is_disetujui) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang ini sedang menunggu persetujuan, tidak dapat dihapus.');
        }

        $barang->update([
            'pending_perubahan' => ['is_delete' => true],
            'is_disetujui' => true,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Penghapusan data telah diajukan.');

    }

    public function cetak_pdf()
    {
        $dataBarangs = DataBarang::with('category')->get();

        $html = view('pages.barang.cetak_pdf', [
            'dataBarangs' => $dataBarangs,
            'title' => 'Laporan Data Barang',
        ])->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('laporan_barang.pdf', 'I');
    }

    // public function request_persetujuan()
    // {
    //     // KOREKSI: Pagination() bukan pagination() dan variabel is_disetujui
    //     $databarang = DataBarang::where('is_disetujui', true)
    //         ->with('category')
    //         ->paginate(10); // Gunakan paginate, bukan pagination

    //     return view('pages.persetujuan.index', compact('databarang'));
    // }
}
