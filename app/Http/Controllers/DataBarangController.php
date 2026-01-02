<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class DataBarangController extends Controller
{
    public function index()
    {
        $dataBarangs = DataBarang::with('category')->paginate(10);
        $categories = Category::all();

        return view('pages.barang.index', compact('dataBarangs', 'categories'));
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
        $categories = Category::all();

        return view('pages.barang.edit', compact('barang', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $barang = DataBarang::findOrFail($id);

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'k_barang' => 'required|string|unique:data_barangs,k_barang,' . $barang->id,
            'jml_stok' => 'required|integer|min:0',
        ]);

        $barang->update($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(DataBarang $barang)
    {
        $barang->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
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
}
